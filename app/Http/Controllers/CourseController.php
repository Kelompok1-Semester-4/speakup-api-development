<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Course;
use App\Models\DetailCourse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr;

class CourseController extends Controller
{

    public function index(Request $request)
    {

        $id = $request->input('id');
        $course_type_id = $request->input('course_type_id');
        $name = $request->input('name');

        if ($name) {
            $courses = Course::with(['detailCourse', 'detailUser'])->where('name', 'like', '%' . $name . '%')->get();
            return $courses;
        }

        if ($id) {
            return Course::with(['detailCourse', 'detailUser'])->find($id);
        }

        if ($course_type_id) {
            return Course::with(['detailCourse', 'detailUser'])->where('course_type_id', $course_type_id)->get();
        }

        $course = Course::with(['courseType', 'detailUser', 'detailCourse'])->get();
        return response()->json($course);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|unique:courses,title',
                'course_type_id' => 'required|integer|exists:course_types,id',
                'price' => 'required',
                'benefit' => 'nullable',
                'thumbnail' => 'required',
            ]);

            $user = Auth::user();
            $course = Course::create([
                'detail_user_id' => $user->id,
                'title' => $request->input('title'),
                'course_type_id' => $request->input('course_type_id'),
                'price' => $request->input('price'),
                'benefit' => $request->input('benefit'),
                'thumbnail' => $request->input('thumbnail'),
            ]);

            $course = Course::with(['courseType', 'detailUser', 'detailCourse'])->find($course->id);

            return ResponseFormatter::success($course, 'Successfully created course');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|unique:courses,title',
                'course_type_id' => 'required|integer|exists:course_types,id',
                'price' => 'required',
                'benefit' => 'nullable',
                'thumbnail' => 'required',
            ]);

            $user = Auth::user();
            $course = Course::find($id);

            if ($course->detail_user_id != $user->id) {
                return ResponseFormatter::error('You are not authorized to update this course');
            }

            $course->detail_user_id = $user->id;
            $course->title = $request->input('title');
            $course->course_type_id = $request->input('course_type_id');
            $course->price = $request->input('price');
            $course->benefit = $request->input('benefit');
            $course->thumbnail = $request->input('thumbnail');

            $course->save();

            $course = Course::with(['courseType', 'detailUser', 'detailCourse'])->find($course->id);

            return ResponseFormatter::success($course, 'Successfully updated course');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), $th->getCode(), $th->getTrace());
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $course = Course::find($id);

            if ($course->detail_user_id != $user->id) {
                return ResponseFormatter::error('You are not allowed to delete this course');
            }

            $course->delete();
            return ResponseFormatter::success('Successfully deleted course');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), $th->getCode(), $th->getTrace());
        }
    }

    public function storeDetailCourse(Request $request, $id)
    {
        try {
            $request->validate([
                'course_id' => 'required|integer|exists:courses,id',
                'title' => 'required|string|unique:detail_courses,title',
                'description' => 'required|string',
                'video_link' => 'required|string',
                'cover_image' => 'required|string',
                'duration' => 'required|string',
            ]);

            $user = Auth::user();
            $course = Course::find($id);

            if ($course->detail_user_id != $user->id) {
                return ResponseFormatter::error('You are not authorized to update this course');
            }

            $detailCourse = $course->detailCourse()->create([
                'course_id' => $id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'video_link' => $request->input('video_link'),
                'cover_image' => $request->input('cover_image'),
                'duration' => $request->input('duration'),
            ]);

            $detailCourse = $course->detailCourse()->find($detailCourse->id);

            return ResponseFormatter::success($detailCourse, 'Successfully created detail course');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), $th->getCode(), $th->getTrace());
        }
    }

    public function updateDetailCourse(Request $request, $id)
    {
        try {
            $request->validate([
                'course_id' => 'required|integer|exists:courses,id',
                'title' => 'required|string|unique:detail_courses,title',
                'description' => 'required|string',
                'video_link' => 'required|string',
                'cover_image' => 'required|string',
                'duration' => 'required|string',
            ]);

            $user = Auth::user();
            $detailCourse = DetailCourse::find($id);

            if ($detailCourse->course->detail_user_id != $user->id) {
                return ResponseFormatter::error('You are not authorized to update this course');
            }

            $detailCourse->course_id = $request->input('course_id');
            $detailCourse->title = $request->input('title');
            $detailCourse->description = $request->input('description');
            $detailCourse->video_link = $request->input('video_link');
            $detailCourse->cover_image = $request->input('cover_image');
            $detailCourse->duration = $request->input('duration');
            $detailCourse->save();

            return ResponseFormatter::success($detailCourse, 'Successfully updated detail course');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), $th->getCode(), $th->getTrace());
        }
    }

    public function destroyDetailCourse($id)
    {
        try {
            $user = Auth::user();
            $detailCourse = DetailCourse::find($id);
            $detailCourse->delete();

            return ResponseFormatter::success('Successfully deleted detail course');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), $th->getCode(), $th->getTrace());
        }
    }
}
