<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function scopeOfFirmId($query, $firmId)
    {
        if ($firmId !== null) {
            return $query->where('firm_id', $firmId);
        }
    }
}
