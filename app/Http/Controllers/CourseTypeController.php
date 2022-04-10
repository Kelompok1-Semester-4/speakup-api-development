<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Illuminate\Http\Request;

class CourseTypeController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');

        if($id) {
            $courseType = CourseType::with(['courses'])->find($id);
            return $courseType;
        }

        $courseTypes = CourseType::with(['courses'])->get();
        return $courseTypes;
    }
}
