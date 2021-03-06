<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Diary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiaryController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $diary_type_id = $request->input('diary_type_id');
        $search = $request->input('search');

        if ($id) {
            return Diary::with(['diaryType', 'detailUser'])->find($id);
        }

        if ($diary_type_id) {
            return Diary::with(['diaryType', 'detailUser'])->where('diary_type_id', $diary_type_id)->get();
        }

        if ($search) {
            // multiple search query by title, content, detail_user.name
            return Diary::with(['diaryType', 'detailUser'])
                ->where('title', 'like', "%$search%")
                ->orWhereHas('detailUser', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                })
                ->get();
        }

        return Diary::with(['diaryType', 'detailUser'])->get();
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|unique:diaries,title',
                'content' => 'required|string',
                'duration_read' => 'required|string',
                'file' => 'nullable|string',
                'cover_image' => 'required|mimes:jpeg,jpg,png,gif',
                'diary_type_id' => 'required|integer|exists:diary_types,id',
            ]);
            $user = Auth::user();
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $fileName = $file->getClientOriginalName();
                // generete random name
                $fileName = uniqid() . '_' . trim($fileName);
                $file->move(public_path('diaries'), $fileName);

                $diary = new Diary();
                $diary->title = $request->input('title');
                $diary->content = $request->input('content');
                $diary->duration_read = $request->input('duration_read');
                $diary->file = $request->input('file');
                $diary->cover_image = 'diaries/' . $fileName;
                $diary->diary_type_id = $request->input('diary_type_id');
                $diary->detail_user_id = $user->id;
                $diary->save();
                return ResponseFormatter::success($diary, 'Diary created successfully');
            } else{
                return ResponseFormatter::error('Cover image is required');
            }

        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'content' => 'required|string',
                'file' => 'nullable|string',
                'duration_read' => 'required|string',
                'diary_type_id' => 'required|integer|exists:diary_types,id',
            ]);

            $user = Auth::user();
            $diary = Diary::with(['detailUser'])->find($id);

            if ($diary->detail_user_id != $user->id) {
                return ResponseFormatter::error('You are not authorized to update this diary');
            }

            if($request->hasFile('cover_image')) {
                $request->validate([
                    'cover_image' => 'mimes:jpeg,jpg,png,gif',
                ]);
                // delete old cover image
                $oldFile = $diary->cover_image;
                if (file_exists(public_path($oldFile))) {
                    unlink(public_path($oldFile));
                }
                $file = $request->file('cover_image');
                $fileName = $file->getClientOriginalName();
                // generete random name
                $fileName = uniqid() . '_' . trim($fileName);
                $file->move(public_path('diaries'), $fileName);

                // update
                $diary->title = $request->input('title');
                $diary->content = $request->input('content');
                $diary->duration_read = $request->input('duration_read');
                $diary->cover_image = 'diaries/' . $fileName;
                $diary->diary_type_id = $request->input('diary_type_id');
                $diary->save();

                return ResponseFormatter::success($diary, 'Diary updated successfully');
            } else {
                // update without cover image
                $diary->title = $request->input('title');
                $diary->content = $request->input('content');
                $diary->duration_read = $request->input('duration_read');
                $diary->diary_type_id = $request->input('diary_type_id');
                $diary->save();

                return ResponseFormatter::success($diary, 'Diary updated successfully');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $diary = Diary::with(['detailUser'])->find($id);

            if ($diary->detail_user_id != $user->id) {
                return ResponseFormatter::error('You are not authorized to delete this diary');
            }

            // delete old cover image
            $oldFile = $diary->cover_image;
            if (file_exists(public_path($oldFile))) {
                unlink(public_path($oldFile));
            }
            $diary->delete();
            return ResponseFormatter::success($diary, 'Diary deleted successfully');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function show(Request $request)
    {
        $user = $request->user();
        return ResponseFormatter::success(Diary::where('detail_user_id', $user->id)->get());
    }
}
