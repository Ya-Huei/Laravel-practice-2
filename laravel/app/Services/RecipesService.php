<?php

namespace App\Services;

use App\Models\Recipe;

class RecipesService
{
    public static function getRecipesOptions()
    {
        $recipes = Recipe::select('recipe')->get();
        $result = $recipes->pluck("recipe")->toArray();
        array_unshift($result, "");
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
