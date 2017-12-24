<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        // No se puede acceder porque laravel convierte transactions en una coleccion y  se pierde la relación
        // $products = $buyer->transactions->product;

        // Usamos Eager Loading y ya no accedemos a la relación sino al query builder
        // Con pluck solo traemos la coleccion que queremos
        $products = $buyer->transactions()->with('product')
            ->get()
            ->pluck('product');

        return $this->showAll($products);
    }

}
