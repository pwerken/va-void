<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Core\Configure;

class Json
{
    public static function decode(?string $data): mixed
    {
        if ($data === null) {
            return null;
        }

        return json_decode($data, true);
    }

    public static function encode(mixed $data, bool $pretty = true): ?string
    {
        if ($data === null) {
            return null;
        }

        $jsonOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
        if (Configure::read('debug') && $pretty) {
            $jsonOptions = $jsonOptions | JSON_PRETTY_PRINT;
        }

        return json_encode($data, $jsonOptions);
    }
}
