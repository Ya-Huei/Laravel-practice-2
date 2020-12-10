<?php

namespace App\Services;

use App\Models\RecipeStep;
use Illuminate\Support\Facades\Log;
class RecipeStepService
{
    public static function getRecipeStepOptions()
    {
        $steps = RecipeStep::all()->toArray();
        $result = [];
        foreach ($steps as $key => $value) {
            $result[$key]['value'] = strval($value['value']);
            $result[$key]['label'] = $value['step'];
            $result[$key]['unit'] = $value['unit'];
        }
        return $result;
    }
}
