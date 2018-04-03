<?php

namespace App\Providers;

use App\Buyer;
use App\Policies\BuyerPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SellerPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use App\Product;
use App\Seller;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Buyer::class => BuyerPolicy::class,
        Seller::class => SellerPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Passport routes
         */
        Passport::routes();

        /**
         * Passport expire token
         */
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30)); // Sin Carbon nunca expira

        /**
         * Passport refresh expired token
         */
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

        /**
         * Enable implicit grant, obtener token automaticamente cuando no se pueden almacenar las credenciales unicamente por ejemplo en ciertas app moviles
         */
        Passport::enableImplicitGrant();

        /**
         * Passport token scouts, permitir acceder a funcionalidades mediante la validación del token
         */
        Passport::tokensCan([
            'purchase-product'  => 'Crear transacciones para comprar productos determinados.',
            'manage-products'   => 'Crear, ver, actualizar y eliminar productos.',
            'manage-account'    => 'Obtener la información de la cuenta, nombre, email, estado (sin contraseña), modificar datos como email, nombre y contraseña. No puede eliminar la cuenta.',
            'read-general'      => 'Obtener información general, categorías donde se compra y se vende, productos vendidos o comprados, transacciones, compras y ventas.',
        ]);

        /**
         * Gate para controlar el resto de acciones no protegidas por las policies
         * @param type 'admin-action'
         * @param $user
         * @return boolean
         */
        Gate::define('admin-action', function ($user)
        {
            return $user->userAdmin();
        });
    }
}
