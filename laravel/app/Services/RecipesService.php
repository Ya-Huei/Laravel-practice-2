<?php

namespace App\Services;

use App\Models\Recipe;

class RecipesService
{
    public static function getRecipesOptions()
    {
        $recipes = Recipe::ofFirmId(auth()->user()->firm_id)->get()->toArray();
        $result = [];
        foreach ($recipes as $key => $value) {
            $result[$key]['value'] = $value['id'];
            $result[$key]['label'] = $value['name'];
        }
        return $result;
    }

    public static function geRecipeInfo($recipe_id)
    {
        $recipe = Recipe::where('id', $recipe_id)->first();
        return $recipe;
    }
}
