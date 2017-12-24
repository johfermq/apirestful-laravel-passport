<?php

namespace App;

use App\Product;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;

class Seller extends User
{
    /**
     ** Transformers
     **/
    public $transformer = SellerTransformer::class;

	/**
	 ** Global Scopes
	 **/
	protected static function boot()
	{
		parent::boot(); //Para mantener el funcionamiento normal de la clase

		static::addGlobalScope(new SellerScope);
	}

    /**
     ** Relaciones
     **/
    public function products()
    {
    	return $this->hasMany(Product::class);
    }

}
