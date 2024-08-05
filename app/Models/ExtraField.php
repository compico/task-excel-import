<?php

namespace App\Models;

use App\Collection\ExtraFieldCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraField extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'key',
        'value',
    ];

    public $timestamps = false;

    public function product() {
        return $this->hasOne(Product::class);
    }

    public function newCollection(array $models = []): ExtraFieldCollection
    {
        return new ExtraFieldCollection($models);
    }
}
