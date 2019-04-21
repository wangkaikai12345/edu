<?php

namespace App\Tools;

use AetherUpload\Util as BaseUtil;
use Ramsey\Uuid\Uuid;

class Util extends BaseUtil
{
    /**
     *
     *
     * @return string
     * @throws \Exception
     */
    public static function generateTempName()
    {
        return time() . '_' . Uuid::uuid4()->getHex();
    }
}