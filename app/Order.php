<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PENDING = 0;
    const UNCONFIRMED = 1;
    const CONFIRMED = 2;
    const REJECTED = 3;
    const ORDER = 0;
    const PREORDER = 1;
    const BACKORDER = 2;

    public $timestamps = false;

    protected $fillable = ['status','type'];

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getOrderTypeAttribute()
    {
        if ($this->type === Order::ORDER) {
            return "Order";
        }  elseif ($this->type === Order::PREORDER) {
            return "Pre-order";
        }  else {
            return "Back-order";
        }
    }

    public function getOrderStatusAttribute()
    {
        if ($this->status === Order::PENDING) {
            return "Pending";
        }  elseif ($this->status === Order::UNCONFIRMED) {
            return "Unconfirmed";
        }  elseif($this->status == Order::CONFIRMED) {
            return "Confirmed";
        } else {
            return "Rejected";
        }
    }

	public function scopeInCart($query)
	{
		return $query->where('status', Order::PENDING);
	}

    public function scopeUnconfirmedOrder($query)
    {
        return $query->where('status', Order::UNCONFIRMED);
	}

    public function scopeRejectedOrder($query)
    {
        return $query->where('status', Order::REJECTED);
    }

    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    public function scopeBackOrder($query)
    {
        return $query->where('type', Order::BACKORDER);
    }

    public function scopePreorder($query){
        return $query->where('type', Order::PREORDER);
    }

    public function scopeOrder($query){
        return $query->where('type', Order::ORDER);
    }
}