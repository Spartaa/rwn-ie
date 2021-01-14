<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function customerAddress()
    {
        return $this->hasMany(CustomerAddress::class,'customer_id','id');
    }
}
