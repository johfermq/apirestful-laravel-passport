<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        /**
         * Para retornar solo los mensajes de error en la validación y no más detalles si estamos en producción
         */
        if ($exception instanceof Validation)
        {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException)
        {
            $model = Str::lower(class_basename($exception->getModel()));

            return $this->errorResponse("No existe ninguna instancia de {$model} con el id expecificado.", 404);
        }

        if ($exception instanceof NotFoundHttpException)
        {
            return $this->errorResponse("No se encontró la url especificada.", 404);
        }

        if ($exception instanceof AuthenticationException)
        {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException)
        {
            return $this->errorResponse("No tiene permisos para ejecutar ésta acción.", 403);
        }

        if ($exception instanceof MethodNotAllowedHttpException)
        {
            return $this->errorResponse("El método especificado en la petición no es válido.", 405);
        }

        if ($exception instanceof HttpException)
        {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException)
        {
            $code = $exception->errorInfo[1]; // Error al eliminar un registro relacionado con otra tabla

            if ($code == 1451)
            {
                return $this->errorResponse("No se puede eliminar de forma permanente el recurso porque está relacionado con algún otro.", 409); // 409 : Conflicto con el sistema
            }
        }

        if ($exception instanceof TokenMismatchException)
        {
            return back()->withInput(request()->input());
        }

        if (config('app.debug'))
        {
            return parent::render($request, $exception);
        }

        return $this->errorResponse("Se ha producido un error inesperado, inténtelo de nuevo.", 500);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFronted($request))
        {
            return redirect()->guest(route('login'));
        }

        return $this->errorResponse("Usuario no autenticado.", 401);
    }

    /**
     * Funcion Personalizada, tomada de Illuminate\Foundation\Exceptions\Handler
     */

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        if ($this->isFronted($request))
        {
            return $request->expectsJson() ? response()->json($errors, 422) : back()
                ->withInput(request()->input())
                ->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }

    protected function isFronted($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

}
