<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    public $timestamps = true;

    public static function getDefineStatus()
    {
        $statusLists = [
            '1' => 'normal',
            '2' => 'repaire',
            '3' => 'under repair',
            '4' => 'no use',
        ];
        return $statusLists;
    }
}
