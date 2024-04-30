<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    use HasFactory;

    const COOKING_FEE = 0.5;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }

    public function getPrice()
    {
        $totalPrice = $this->ingredients->sum('price');
        $cookingFee = $totalPrice * self::COOKING_FEE;
        $totalPriceWithFee = $totalPrice + $cookingFee;
        $totalPriceWithFee = round($totalPriceWithFee, 2);
        return $totalPriceWithFee;
    }
}
