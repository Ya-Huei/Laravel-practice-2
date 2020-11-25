<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $firmware = $this->formatFirmware($data);
        return response()->json($firmware);
    }

    private function formatFirmware($data)
    {
        $firmware = [];
        foreach ($data as $item) {
            $tmp = [];
            $tmp['id'] = $item->id;
            $tmp['version'] = $item->version;
            $tmp['registered'] = isset($item->created_at) ? $item->created_at->format('Y-m-d H:i:s') : "";
            array_push($firmware, $tmp);
        }
        return $firmware;
    }
}
