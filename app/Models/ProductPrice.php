<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    use HasFactory;

    protected $table = 'product_prices';

    // campos que se pueden llenar 
    protected $fillable = [
        'product_id',
        'currency_id',
        'price',
    ];

    // tipos de datos de los campos
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Producto al que pertenece este precio.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Divisa en la que está expresado este precio.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
