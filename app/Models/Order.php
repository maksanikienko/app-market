<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('count', 'variant_id', 'color', 'color_hex', 'size')
            ->withTimestamps();
    }

//    public function getFullPrice()
//    {
//        $sum = 0;
//        foreach ($this->products as $product) {
//            $sum += $product->getPriceForCount();
//        }
//        return $sum;
//    }
    public function saveOrder($name, $phone): bool
    {
        //status - DB field / $name $phone from Request
        if ($this->status == 0) {
            $this->name = $name;
            $this->phone = $phone;
            $this->status = 1;
            $this->save();
            session()->forget('orderId');
            return true;
        }

        return false;
    }
}
