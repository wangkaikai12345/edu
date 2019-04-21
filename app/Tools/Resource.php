<?php

namespace App\Tools;

use AetherUpload\Resource as BaseResource;


class Resource extends BaseResource
{

    public function getSavedPath()
    {
        return $this->group . '/' . $this->groupSubDir . '/' . $this->name;
    }
}