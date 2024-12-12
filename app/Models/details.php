<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'user_id', // Tambahkan user_id ke dalam fillable
    ];

    // Jika Anda ingin mendefinisikan relasi dengan Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Jika Anda ingin mendefinisikan relasi dengan Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Jika Anda ingin mendefinisikan relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
