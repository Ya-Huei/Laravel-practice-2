<?php

namespace App\Enums;

abstract class Statuses
{
    const ENABLE = 1;
    const DISABLE = 2;
    const REPAIR = 3;
    const FAULT = 4;
    const IDLE = 5;
    const UPDATING = 6;
    const SUCCESS = 7;
    const FAIL = 8;
}
