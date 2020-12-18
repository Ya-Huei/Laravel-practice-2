<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\Recipes\RecipeCopyValidation;
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
        $step = 0;
        foreach ($request->recipes as $key => $value) {
            $this->checkValue($recipeStep, $value);
            $step++;
            if ($value['step'] === "0" || $step > 50) {
                break;
            }
        }
        $result = implode(",", $recipeStep);
        
        $recipe = new Recipe();
        $recipe->name = $request->recipeName;
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
        $step = 0;
        foreach ($request->recipes as $key => $value) {
            $this->checkValue($recipeStep, $value);
            $step++;
            if ($value['step'] === "0" || $step > 50) {
                break;
            }
        }
        $result = implode(",", $recipeStep);
        
        $recipe->name = $request->recipeName;
        $recipe->recipe = $result;
        $recipe->save();
    }

    public function copy(RecipeCopyValidation $request, Recipe $recipe)
    {
        $copyRecipe = new Recipe();
        $copyRecipe->name = $recipe->name . time();
        $copyRecipe->recipe = $recipe->recipe;
        $copyRecipe->firm_id = $recipe->firm_id;
        $copyRecipe->save();

        return response()->json(['status' => 'success']);
    }

    private function checkValue(&$recipeStep, $value)
    {
        $step = isset($value['step']) ? intval($value['step']) : 0;
        $para = isset($value['para']) ? intval($value['para']) : 0;
        $act1 = isset($value['act1']) ? intval($value['act1']) : 0;
        $act2 = isset($value['act2']) ? intval($value['act2']) : 0;
        $act3 = isset($value['act3']) ? intval($value['act3']) : 0;
        $unit = isset($value['unit']) ? $value['unit'] : 0;
        
        array_push($recipeStep, $step);
        array_push($recipeStep, $this->checkParaByUnit($unit, $para));
        array_push($recipeStep, $this->checkAct($act1));
        array_push($recipeStep, $this->checkAct($act2));
        array_push($recipeStep, $this->checkAct($act3));
    }

    private function checkParaByUnit($unit, $value)
    {
        Log::info('unit: ' . $unit);
        if ($unit === 0) {
            return 0;
        }

        $value = $value < 0 || !is_numeric($value) ? 0 : $value;
        switch ($unit) {
            case "L":
                $value = $value > 24 ? 24 : $value;
                break;
            case "℃":
            case "%":
                $value = $value > 100 ? 100 : $value;
                break;
            case "sec":
                $value = $value > 7200 ? 7200 : $value;
                break;
        }
        return $value;
    }

    private function checkAct($value)
    {
        $value = $value < 0 || !is_numeric($value) ? 0 : $value;
        $value = $value > 7200 ? 7200 : $value;
        return $value;
    }
}
