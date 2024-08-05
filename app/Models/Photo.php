<?php

namespace App\Models;

use App\Collection\PhotoCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'url',
        'referer_url',
    ];

    public function product() {
        return $this->hasOne(Product::class);
    }

    public function newCollection(array $models = []): PhotoCollection
    {
        return new PhotoCollection($models);
    }
}
