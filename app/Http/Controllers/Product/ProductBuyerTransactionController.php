<?php

namespace App\Http\Controllers\Product;

use DB;
use App\User;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductBuyerTransactionController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request, $rules);

        if ($buyer->id === $product->seller_id)
        {
            return $this->errorResponse("El comprador debe ser diferente al vendedor.", 409);
        }

        if (!$buyer->userVerified())
        {
            return $this->errorResponse("El comprador debe ser un usuario verificado.", 409);
        }

        if (!$product->seller->userVerified())
        {
            return $this->errorResponse("El vendedor debe ser un usuario verificado.", 409);
        }

        if (!$product->productStatus())
        {
            return $this->errorResponse("El producto para esta transacción no está disponible.", 409);
        }

        if ($product->quantity < $request->quantity)
        {
            return $this->errorResponse("El producto no tiene la cantidad disponible requerida para esta transacción.", 409);
        }

        return DB::transaction(function () use ($request, $product, $buyer)
        {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'seller_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction, 201); // 201 porque es una creación
        });
    }

}
