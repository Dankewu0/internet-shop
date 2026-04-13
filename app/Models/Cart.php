<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ["user_id", "session_id"];

    protected $appends = ["total_price", "items_count"];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalPriceAttribute(): float
    {
        $items = $this->relationLoaded("items") ? $this->items : $this->items()->with("product")->get();

        return (float) $items->sum(fn(CartItem $item) => $item->quantity * (float) ($item->product?->price ?? 0));
    }

    public function getItemsCountAttribute(): int
    {
        $items = $this->relationLoaded("items") ? $this->items : $this->items()->get();

        return (int) $items->sum("quantity");
    }
}
