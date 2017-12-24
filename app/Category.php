<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

   	protected $fillable = [
        'name',
        'description'
    ];

    protected $dates = [
        'delete_at'
    ];

    protected $hidden = [ // Ocultar el atributo "pivot" en la relación al mostrar los resultados en JSON
        'pivot'
    ];

    /**
     ** Transformers
     **/
    public $transformer = CategoryTransformer::class;

    /**
     ** Relaciones
     **/
    public function products()
    {
    	return $this->belongsToMany(Product::class);
    }

}
