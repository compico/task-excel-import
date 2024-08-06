<?php

declare(strict_types=1);

namespace App\Models;

use App\Collection\PhotoCollection;
use App\Collection\ProductCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_code',
        'name',
        'description',
        'price',
        'discount',
    ];

    public $timestamps = false;

    public function newCollection(array $models = []): ProductCollection
    {
        return new ProductCollection($models);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
