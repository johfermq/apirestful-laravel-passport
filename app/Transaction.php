<?php

namespace App;

use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'
    ];

    protected $dates = [
        'delete_at'
    ];

    /**
     ** Transformers
     **/
    public $transformer = TransactionTransformer::class;

    /**
     ** Relaciones
     **/
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
