<?php

namespace App\Http\Controllers;

use App\Models\DiaryType;
use Illuminate\Http\Request;

class DiaryTypeController extends Controller
{
    public function index(Request $request) {
        $id = $request->input('id');

        if($id) {
            return DiaryType::with('diaries')->find($id);
        }

        return DiaryType::with('diaries')->get();
    }
}
