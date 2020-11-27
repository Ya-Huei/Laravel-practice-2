<?php

namespace App\Services;

use App\Models\Recipe;

class RecipesService
{
    public static function getAllRecipe()
    {
        $result = [];
        $defaultOption = "";
        array_push($result, $defaultOption);
        $recipes = Recipe::select('recipe')->get();
        foreach ($recipes as $item) {
            array_push($result, $item->recipe);
        }
        return $result;
    }

    public static function getRecipeId($item)
    {
        $recipe = Recipe::where('recipe', $item)->first();
        return $recipe->id;
    }

    public static function geRecipeInfo($recipe_id)
    {
        $recipe = Recipe::where('id', $recipe_id)->first();
        return $recipe;
    }
}
