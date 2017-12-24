<?php

namespace App;

use App\Transaction;
use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;

class Buyer extends User
{
    /**
     ** Transformers
     **/
    public $transformer = BuyerTransformer::class;

	/**
	 ** Global Scopes
	 **/
	protected static function boot()
	{
		parent::boot(); //Para mantener el funcionamiento normal de la clase

		static::addGlobalScope(new BuyerScope);
	}

    /**
     ** Relaciones
     **/
    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }

}
