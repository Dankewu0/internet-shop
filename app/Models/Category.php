<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ["name", "slug", "parent_id"];

    protected static function booted(): void
    {
        static::saving(function (Category $category): void {
            if (blank($category->slug)) {
                $base = Str::slug($category->name);
                $slug = $base;
                $i = 1;

                while (
                    static::query()
                        ->where("slug", $slug)
                        ->when(
                            $category->exists,
                            fn($q) => $q->where("id", "!=", $category->id)
                        )
                        ->exists()
                ) {
                    $slug = $base . "-" . $i++;
                }

                $category->slug = $slug;
            }
        });
    }

    public function level(): int
    {
        $level = 1;
        $current = $this->parent;

        while ($current !== null) {
            $level++;
            $current = $current->parent;
        }

        return $level;
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, "parent_id");
    }

    public function children()
    {
        return $this->hasMany(Category::class, "parent_id");
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
