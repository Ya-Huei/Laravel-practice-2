<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\Recipes\RecipeEditValidation;
use App\Http\Requests\Recipes\RecipeStoreFormValidation;
use App\Http\Requests\Recipes\RecipeUpdateFormValidation;
use App\Http\Resources\RecipeCollection;
use App\Models\Recipe;
use App\Services\RecipeActionService;
use App\Services\RecipeStepService;

class RecipesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Recipe::with('firm')
            ->orderBy('id', 'asc')
            ->ofFirmId(auth()->user()->firm_id)
            ->get();

        $users = new RecipeCollection($data);
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $steps = RecipeStepService::getRecipeStepOptions();
        $actions = RecipeActionService::getRecipeActionOptions();
        return response()->json(compact('steps', 'actions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecipeStoreFormValidation $request)
    {
        $recipeStep = [];
        foreach ($request->recipes as $key => $value) {
            isset($value['step']) ? array_push($recipeStep, intval($value['step'])) : array_push($recipeStep, 0);
            isset($value['para']) ? array_push($recipeStep, $value['para']) : array_push($recipeStep, 0);
            isset($value['act1']) ? array_push($recipeStep, intval($value['act1'])) : array_push($recipeStep, 0);
            isset($value['act2']) ? array_push($recipeStep, intval($value['act2'])) : array_push($recipeStep, 0);
            isset($value['act3']) ? array_push($recipeStep, intval($value['act3'])) : array_push($recipeStep, 0);
            if ($value['step'] === "0") {
                break;
            }
        }
        $result = implode(",", $recipeStep);
        
        $recipe = new Recipe();
        $recipe->name = $request->name;
        $recipe->recipe = $result;
        $recipe->firm_id = auth()->user()->firm_id;
        $recipe->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RecipeEditValidation $request, Recipe $recipe)
    {
        // Log::info(print_r($recipe));
        $steps = RecipeStepService::getRecipeStepOptions();
        $actions = RecipeActionService::getRecipeActionOptions();
        return response()->json(compact('steps', 'actions', 'recipe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecipeUpdateFormValidation $request, Recipe $recipe)
    {
        $recipeStep = [];
        foreach ($request->recipes as $key => $value) {
            isset($value['step']) ? array_push($recipeStep, intval($value['step'])) : array_push($recipeStep, 0);
            isset($value['para']) ? array_push($recipeStep, $value['para']) : array_push($recipeStep, 0);
            isset($value['act1']) ? array_push($recipeStep, intval($value['act1'])) : array_push($recipeStep, 0);
            isset($value['act2']) ? array_push($recipeStep, intval($value['act2'])) : array_push($recipeStep, 0);
            isset($value['act3']) ? array_push($recipeStep, intval($value['act3'])) : array_push($recipeStep, 0);
            if ($value['step'] === "0") {
                break;
            }
        }
        $result = implode(",", $recipeStep);
        
        $recipe->name = $request->name;
        $recipe->recipe = $result;
        $recipe->save();
    }
}
