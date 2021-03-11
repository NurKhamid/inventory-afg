<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
		'customer_id',
		'product_id',
		'quantity',
		'order_date'
	];

	protected $dates = ['order_date'];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function setOrderDateAttribute($date)
	{
		$this->attributes['order_date']  = Carbon::parse($date);
	}
}
