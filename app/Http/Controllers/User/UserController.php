<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();

        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $reglas);

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USER_NO_VERIFIED;
        $campos['verification_token'] = User::generateVerificationToken();
        $campos['admin'] = User::USER_REGULAR;

        $usuario = User::create($campos);

        return $this->showOne($usuario, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // $user = User::findOrFail($id);

        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // $user = User::findOrFail($id);

         $reglas = [
            'email' => 'email|unique:users,email,'. $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USER_ADMIN. ',' . User::USER_REGULAR,
        ];

        $this->validate($request, $reglas);

        if ($request->has('name'))
        {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email)
        {
            $user->email = $request->email;
            $user->verified = User::USER_NO_VERIFIED;
            $user->verification_token = User::generateVerificationToken();
        }

        if ($request->has('password'))
        {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin'))
        {
            if (!$user->userVerified())
            {
                return $this->errorResponse('Únicamente los usuarios verificados pueden cambiar su valor de administrador.', 409); //409 = Conflicto en la petición
            }

            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) //isDirty = Verifica si hay un cambio de datos en el registro (Compara).
        {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar.', 422); //422 = Pertición mal formada
        }

        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // $user = User::findOrFail($id);

        $user->delete();

        return $this->showOne($user);
    }

    /**
     * Verify the account of an user registered.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::USER_VERIFIED;
        $user->verification_token = null;
        $user->save();

        return $this->showMessage("La cuenta ha sido verificada con éxito.");
    }

    /**
     * Resend the email of verification of the account of user registered.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function resend(User $user)
    {
        if ($user->userVerified())
        {
            return $this->errorResponse('El usuario ya ha sido verificado.', 409);
        }

        retry(5, function() use ($user)
        {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage("El correo de verificación se ha reenviado.");
    }

}
