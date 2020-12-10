<?php

namespace App\Services;

use App\Models\RecipeAction;

class RecipeActionService
{
    public static function getRecipeActionOptions()
    {
        $actions = RecipeAction::all()->toArray();
        $result = [];
        foreach ($actions as $key => $value) {
            $result[$key]['value'] = $value['value'];
            $result[$key]['label'] = $value['action'];
        }
        return $result;
    }
}
