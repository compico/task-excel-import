<?php

declare(strict_types=1);

namespace App\Cache;

use Illuminate\Support\Facades\Cache;

class ProductsImportCache extends Cache
{
    /** @var string */
    const KEY_FORMAT = 'import_products:%s';

    static public function IsFileImported(string $hash): bool
    {
        $cache = self::get(sprintf(self::KEY_FORMAT, $hash));

        return $cache !== null;
    }

    static public function AddCache(string $hash, int $importId): bool
    {
        return self::add(sprintf(self::KEY_FORMAT, $hash), $importId);
    }
}
