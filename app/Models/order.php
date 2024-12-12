<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
    ];

    // Jika Anda ingin mendefinisikan relasi dengan Detail
    public function details()
    {
        return $this->hasMany(Details::class);
    }
}
