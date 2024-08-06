<?php

namespace App\Repository;

use App\Models\ExtraField;

class ExtraFieldRepository
{
    public static function GetExtraFieldsByProductId(int $productId): ?array
    {
        $extraFields = ExtraField::query()
            ->where('product_id', $productId)
            ->get(['key', 'value']);

        if ($extraFields->isEmpty()) {
            return null;
        }

        $result = [];
        /** @var ExtraField $extraField */
        foreach ($extraFields as $extraField) {
            $result[$extraField->key] = $extraField->value;
        }

        return $result;
    }
}
