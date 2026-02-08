<?php

namespace App\Models;

use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

trait CreateLog
{
    use CausesActivity, LogOption, LogsActivity;

    protected static array $recordEvents = ['created', 'deleted', 'updated'];
}
