<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'vending_machine_id',
        'product_id',
        'stock',
    ];

    public function vendingMachine()
    {
        return $this->belongsTo(VendingMachine::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
