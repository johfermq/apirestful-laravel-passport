<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identificador' => (int) $category->id,
            'titulo' => (string) $category->name,
            'detalles' => (string) $category->description,
            'fechaCreacion' => (string) $category->created_at,
            'fechaActualizacion' => (string) $category->updated_at,
            'fechaEliminacion' => isset($category->delete_at) ? (string) $category->delete_at : null,
            /**
             * HATEOAS
             */
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id),
                ],
                [
                    'rel' => 'category.buyers',
                    'href' => route('categories.buyers.index', $category->id),
                ],
                [
                    'rel' => 'category.products',
                    'href' => route('categories.products.index', $category->id),
                ],
                [
                    'rel' => 'category.sellers',
                    'href' => route('categories.sellers.index', $category->id),
                ],
                [
                    'rel' => 'category.transactions',
                    'href' => route('categories.transactions.index', $category->id),
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
            'titulo' => 'name',
            'detalles' => 'description',
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
            'name' => 'titulo',
            'description' => 'detalles',
            'created_at' => 'fechaCreacion',
            'updated_at' => 'fechaActualizacion',
            'delete_at' => 'fechaEliminacion',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
