<?php

namespace App\Providers;

use App\Product;
use App\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Mail\UserCreated;
use Mail\UserMailChanged;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        /**
         * Evento que envia el email de confirmación de la cuenta al usuario
         * @param User $user
         * @return void
         */
        User::created(function ($user)
        {
            // retry (numero intentos, funcion(), milisegundos de espera)
            retry(5, function() use ($user)
            {
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
        });

        /**
         * Evento que envia el email de verificación del nuevo correo al usuario
         * @param User $user
         * @return void
         */
        User::updated(function ($user)
        {
            /**
             * Se envia el email si el usuario cambio el correo electrónico
             */
            if ($user->isDirty('email'))
            {
                // retry (numero intentos, funcion(), milisegundos de espera)
                retry(5, function() use ($user)
                {
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 100);
            }
        });

        /**
         * Evento que cambia el estado del producto al realizar una transacción
         * @param Product $product
         * @return void
         */
        Product::updated(function ($product)
        {
            if ($product->quantity === 0 && $product->productStatus())
            {
                $product->status = Product::PRODUCT_NO_AVAILABLE;
                $product->save();
            }
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
