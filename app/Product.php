<?php

namespace App;

use App\Seller;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];

    protected $dates = [
        'delete_at'
    ];

    // Ocultar el atributo "pivot" en la relación al mostrar los resultados en JSON
    protected $hidden = [
        'pivot'
    ];

    /**
     ** Transformers
     **/
    public $transformer = ProductTransformer::class;

    /**
	 ** Disponibilidad del producto
	 **/
    const PRODUCT_AVAILABLE = '1';
    const PRODUCT_NO_AVAILABLE = '0';

    public function productStatus()
    {
    	return $this->status === Product::PRODUCT_AVAILABLE;
    }

    /**
     ** Relaciones
     **/
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
