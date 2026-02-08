<?php

namespace App\Models;

trait TableName
{
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
