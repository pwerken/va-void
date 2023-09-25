<?php
namespace App\Utility;

use Cake\Core\Configure;

class Json
{
    public static function encode($data)
    {
        $jsonOptions = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT;
        if(Configure::read('debug'))
            $jsonOptions = $jsonOptions | JSON_PRETTY_PRINT;
        return json_encode($data, $jsonOptions);
    }
}
