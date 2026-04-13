<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        "name",
        "description",
        "slug",
        "category_id",
        "price",
        "attributes",
    ];

    protected $casts = [
        "attributes" => "array",
        "price" => "decimal:2",
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (blank($product->slug)) {
                $base = Str::slug($product->name);
                $slug = $base;
                $i = 1;

                while (
                    static::query()
                        ->where("slug", $slug)
                        ->when(
                            $product->exists,
                            fn($q) => $q->where("id", "!=", $product->id)
                        )
                        ->exists()
                ) {
                    $slug = $base . "-" . $i++;
                }

                $product->slug = $slug;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
