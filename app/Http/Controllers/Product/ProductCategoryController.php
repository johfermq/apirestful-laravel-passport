<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
        // Métodos para añadir datos en relación muchos a muchos
        // @method: sync, attach, syncWithoutDetaching

        // @method sync: Añade la nueva categoria pero elimina las anteriores
        // $product->categories()->sync([$category->id]);

        // @method attach: Añade la nueva categoria pero no verifica si esta duplicada
        // $product->categories()->attach([$category->id]);

        // @method syncWithoutDetaching: Añade la nueva categoria sin eliminar las anteriores y duplicarlas
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id))
        {
            return $this->errorResponse("La categoria especificada no es una categoria de este producto.", 404);
        }

        // Remueve la categoria de la tabla muchos a muchos
        $product->categories()->detach($category->id);

        return $this->showAll($product->categories);
    }
}
