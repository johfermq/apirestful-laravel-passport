<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('scope:read-general')->only(['index']);
        $this->middleware('can:view,buyer')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        // Usamos Eager Loading y ya no accedemos a la relación sino al query builder
        // Con pluck solo traemos la coleccion que queremos
        $categories = $buyer->transactions()->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse() // Une todas las listas en una sola
            ->unique('id') // Muestra sellers sin repetir el id
            ->values(); // Redimensiona la colección y elimina los indices vacios

        return $this->showAll($categories);
    }

}
