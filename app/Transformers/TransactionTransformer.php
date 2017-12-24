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

}
