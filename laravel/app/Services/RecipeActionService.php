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
            $result[$key]['value'] = strval($value['value']);
            $result[$key]['label'] = trans("recipe-action.action" . $value['value']);
        }
        return $result;
    }
}
