<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Course;
use App\Models\DetailCourse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return Course::where('id', $id)->with(['detailCourse', 'detailUser', 'detailCourse'])->first();
        }

        if ($course_type_id) {
            return Course::with(['detailCourse', 'detailUser'])->where('course_type_id', $course_type_id)->get();
        }

        $course = Course::with(['courseType', 'detailUser', 'detailCourse'])->get();
        return response()->json($course);
    }

    public function detailSubCourse($id)
    {
        $detailCourse = DetailCourse::where('id', $id)->first();
        return ResponseFormatter::success($detailCourse);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|unique:courses,title',
                'course_type_id' => 'required|integer|exists:course_types,id',
                'price' => 'required',
                'benefit' => 'nullable',
                'thumbnail' => 'required|mimes:jpeg,jpg,png',
            ]);

            $user = Auth::user();
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnail_name = time() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move(public_path('courses'), $thumbnail_name);

                $course = Course::create([
                    'detail_user_id' => $user->id,
                    'title' => $request->input('title'),
                    'course_type_id' => $request->input('course_type_id'),
                    'price' => $request->input('price'),
                    'benefit' => $request->input('benefit'),
                    'thumbnail' => 'courses/' . $thumbnail_name,
                ]);

                $course = Course::with(['courseType', 'detailUser', 'detailCourse'])->find($course->id);
                return ResponseFormatter::success($course, 'Successfully created course');
            } else {
                return ResponseFormatter::error('Gambar tidak boleh kosong');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|unique:courses,title',
                'course_type_id' => 'required|integer|exists:course_types,id',
                'price' => 'required',
                'benefit' => 'nullable',
            ]);

            $user = Auth::user();
            if($request->hasFile('thumbnail')) {
                $request->validate([
                    'thumbnail' => 'mimes:jpeg,jpg,png',
                ]);
                // delete old thumbnail
                $course = Course::find($id);
                $thumbnail_path = public_path($course->thumbnail);
                if (file_exists($thumbnail_path)) {
                    unlink($thumbnail_path);
                }
                $thumbnail = $request->file('thumbnail');
                $thumbnail_name = time() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move(public_path('courses'), $thumbnail_name);

                $course = Course::find($id);
                $course->update([
                    'detail_user_id' => $user->id,
                    'title' => $request->input('title'),
                    'course_type_id' => $request->input('course_type_id'),
                    'price' => $request->input('price'),
                    'benefit' => $request->input('benefit'),
                    'thumbnail' => 'courses/' . $thumbnail_name,
                ]);

                $course = Course::with(['courseType', 'detailUser', 'detailCourse'])->find($course->id);
                return ResponseFormatter::success($course, 'Successfully updated course');
            } else {
                $course = Course::find($id);
                $course->update([
                    'detail_user_id' => $user->id,
                    'title' => $request->input('title'),
                    'course_type_id' => $request->input('course_type_id'),
                    'price' => $request->input('price'),
                    'benefit' => $request->input('benefit'),
                ]);

                $course = Course::with(['courseType', 'detailUser', 'detailCourse'])->find($course->id);
                return ResponseFormatter::success($course, 'Successfully updated course');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
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
                'course_id' => 'required|exists:courses,id',
                'title' => 'required|string|unique:detail_courses,title',
                'description' => 'required|string',
                'video_link' => 'required|string',
                'cover_image' => 'required|mimes:jpeg,jpg,png',
                'duration' => 'required|string',
            ]);

            $user = Auth::user();
            $course = Course::find($id);

            if ($course->detail_user_id != $user->id) {
                return ResponseFormatter::error('You are not authorized to update this course');
            }

            if($request->hasFile('cover_image')) {
                $cover_image = $request->file('cover_image');
                $cover_image_name = time() . '.' . $cover_image->getClientOriginalExtension();
                $cover_image->move(public_path('courses'), $cover_image_name);

                $detail_course = DetailCourse::create([
                    'course_id' => $request->input('course_id'),
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'video_link' => $request->input('video_link'),
                    'cover_image' => 'courses/' . $cover_image_name,
                    'duration' => $request->input('duration'),
                ]);

                $detail_course = Course::with(['courseType', 'detailUser', 'detailCourse'])->where('id', $id)->first();
                return ResponseFormatter::success($detail_course, 'Successfully created detail course');
            } else {
                return ResponseFormatter::error('Gambar tidak boleh kosong');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function updateDetailCourse(Request $request, $id)
    {
        try {
            $request->validate([
                'course_id' => 'required|exists:courses,id',
                'title' => 'required',
                'description' => 'required|string',
                'video_link' => 'required|string',
                'duration' => 'required|string',
            ]);

            $user = Auth::user();
            $detailCourse = DetailCourse::find($id);

            if ($detailCourse->course->detail_user_id != $user->id) {
                return ResponseFormatter::error('You are not authorized to update this course');
            }

            if($request->hasFile('cover_image')) {
                $request->validate([
                    'cover_image' => 'mimes:jpeg,jpg,png',
                ]);
                // delete old cover_image
                $course = DetailCourse::find($id);
                $cover_image_path = public_path($course->cover_image);
                if (file_exists($cover_image_path)) {
                    unlink($cover_image_path);
                }
                $cover_image = $request->file('cover_image');
                $cover_image_name = time() . '.' . $cover_image->getClientOriginalExtension();
                $cover_image->move(public_path('courses'), $cover_image_name);

                $detail_course = DetailCourse::find($id);
                $detail_course->update([
                    'course_id' => $request->input('course_id'),
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'video_link' => $request->input('video_link'),
                    'cover_image' => 'courses/' . $cover_image_name,
                    'duration' => $request->input('duration'),
                ]);

                $detail_course = Course::with(['courseType', 'detailUser', 'detailCourse'])->where('id', $detailCourse->course_id)->first();
                return ResponseFormatter::success($detail_course, 'Successfully updated detail course');
            } else {
                $detail_course = DetailCourse::find($id);
                $detail_course->update([
                    'course_id' => $request->input('course_id'),
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'video_link' => $request->input('video_link'),
                    'duration' => $request->input('duration'),
                ]);

                $detail_course = Course::with(['courseType', 'detailUser', 'detailCourse'])->where('id', $detailCourse->course_id)->first();
                return ResponseFormatter::success($detail_course, 'Successfully updated detail course');
            }

        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function destroyDetailCourse($id)
    {
        try {
            $user = Auth::user();
            $detailCourse = DetailCourse::find($id);
            // delete old cover_image
            $cover_image_path = public_path($detailCourse->cover_image);
            if (file_exists($cover_image_path)) {
                unlink($cover_image_path);
            }
            $detailCourse->delete();

            return ResponseFormatter::success('Successfully deleted detail course');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), $th->getCode(), $th->getTrace());
        }
    }

    public function conselorCourses(Request $request)
    {
        $user = $request->user();
        $courses = $user->courses()->with(['detailCourse'])->get();

        return ResponseFormatter::success($courses, 'Successfully get courses');
    }
}
