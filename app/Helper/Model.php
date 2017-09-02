<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Helper\Builder as BaseBuilder;


class Model extends BaseModel
{
    public function newEloquentBuilder($query)
    {
        return new BaseBuilder($query);
    }

}