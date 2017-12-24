<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identificador' => (int) $product->id,
            'titulo' => (string) $product->name,
            'detalles' => (string) $product->description,
            'disponibles' => (string) $product->quantity,
            'estado' => (string) $product->status,
            'imagen' => url("img/{$product->image}"),
            'vendedor' => (int) $product->seller_id,
            'fechaCreacion' => (string) $product->created_at,
            'fechaActualizacion' => (string) $product->updated_at,
            'fechaEliminacion' => isset($product->delete_at) ? (string) $product->delete_at : null,
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
            'titulo' => 'name',
            'detalles' => 'description',
            'disponibles' => 'quantity',
            'estado' => 'status',
            'imagen' => 'image',
            'vendedor' => 'seller_id',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'delete_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
