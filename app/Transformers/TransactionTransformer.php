<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identificador' => (int) $transaction->id,
            'cantidad' => (int) $transaction->quantity,
            'comprador' => (string) $transaction->buyer_id,
            'producto' => (string) $transaction->product_id,
            'fechaCreacion' => (string) $transaction->created_at,
            'fechaActualizacion' => (string) $transaction->updated_at,
            'fechaEliminacion' => isset($transaction->delete_at) ? (string) $transaction->delete_at : null,
            /**
             * HATEOAS
             */
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $transaction->id),
                ],
                [
                    'rel' => 'transaction.categories',
                    'href' => route('transactions.categories.index', $transaction->id),
                ],
                [
                    'rel' => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $transaction->id),
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', $transaction->buyer_id),
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', $transaction->product_id),
                ],
            ],
        ];
    }

    /**
     * Original attribute
     * @param type $index
     * @return string
     */
    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador' => 'id',
            'cantidad' => 'quantity',
            'comprador' => 'buyer_id',
            'producto' => 'product_id',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'delete_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    /**
     * Attribute transformed
     * @param type $index
     * @return string
     */
    public static function transformedAttribute($index)
    {
        $attributes = [
            'id' => 'identificador',
            'quantity' => 'cantidad',
            'buyer_id' => 'comprador',
            'product_id' => 'producto',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'delete_at' => 'fechaEliminacion',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
