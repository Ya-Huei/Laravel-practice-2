<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceHasGroups extends Model
{
    use HasFactory;
    protected $table = 'device_has_groups';
    public $timestamps = false;
}