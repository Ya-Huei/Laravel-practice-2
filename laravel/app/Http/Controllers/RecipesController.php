<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Recipes\RecipeStoreFormValidation;
use App\Http\Resources\RecipeCollection;
use Illuminate\Support\Facades\Log;
use App\Models\Recipe;
use App\Services\RecipeStepService;
use App\Services\RecipeActionService;

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
            isset($value['act1']) ? array_push($recipeStep, $value['act1']) : array_push($recipeStep, 0);
            isset($value['act2']) ? array_push($recipeStep, $value['act2']) : array_push($recipeStep, 0);
            isset($value['act3']) ? array_push($recipeStep, $value['act3']) : array_push($recipeStep, 0);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
