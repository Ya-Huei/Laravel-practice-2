<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FirmwareCollection;
use App\Models\Firmware;

class FirmwareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Firmware::orderBy('id', 'desc')->get();
        $firmware = new FirmwareCollection($data);
        return response()->json($firmware);
    }
}
