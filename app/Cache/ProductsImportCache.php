<?php

declare(strict_types=1);

namespace App\Cache;

use Illuminate\Support\Facades\Cache;

class ProductsImportCache extends Cache
{
    /** @var string */
    protected const KEY_FORMAT = 'import_products:%s';
    public const STATUS_IMPORTED = 'imported';

    public static function IsFileImported(string $hash): bool
    {
        $cache = self::get(sprintf(self::KEY_FORMAT, $hash));

        return $cache !== null ;
    }

    public static function SetImportStatus(string $hash, string $status): bool
    {
        return self::add(sprintf(self::KEY_FORMAT, $hash), $status);
    }
}
