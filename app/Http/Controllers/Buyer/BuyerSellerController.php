<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        // Usamos Eager Loading y ya no accedemos a la relación sino al query builder
        // Con pluck solo traemos la coleccion que queremos
        $sellers = $buyer->transactions()->with('product.seller')
            ->get()
            ->pluck('product.seller')
            ->unique('id') // Muestra sellers sin repetir el id
            ->values(); // Redimensiona la colección y elimina los indices vacios

        return $this->showAll($sellers);
    }

}
