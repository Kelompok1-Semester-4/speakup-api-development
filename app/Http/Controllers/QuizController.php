<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\Request;

class QuizController extends Controller
{

    public function index(Request $request)
    {
        $id = $request->input('id');
        $quiz = Quiz::with('detailQuiz')->get();

        if ($id) {
            return ResponseFormatter::success($quiz->find($id));
        }

        return ResponseFormatter::success($quiz);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->role->id == 3) {
            try {
                $request->validate([
                    'title' => 'required|string|max:255',
                    'photo' => 'required|image|mimes:jpeg,png,jpg',
                    'description' => 'required|string|max:255',
                ]);

                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $photoName = time() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('quiz'), $photoName);

                    $quiz = Quiz::create([
                        'title' => $request->title,
                        'photo' => 'quiz/' . $photoName,
                        'description' => $request->description,
                    ]);

                    return ResponseFormatter::success($quiz, 'Quiz successfully created');
                } else {
                    return ResponseFormatter::error('Photo is required', 400);
                }
            } catch (Exception $th) {
                return ResponseFormatter::error($th->getMessage());
            }
        } else {
            return ResponseFormatter::error('You are not allowed to do this action.');
        }
    }

    public function update(Request $request, $id)
    {
        $user =  $request->user();
        if($user->role->id == 3) {
            try {
                $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'required|string|max:255',
                ]);

                $quiz = Quiz::find($id);
                if ($request->hasFile('photo')) {
                    $request->validate([
                        'photo' => 'image|mimes:jpeg,png,jpg',
                    ]);
                    // delete old photo
                    if (file_exists(public_path($quiz->photo))) {
                        unlink(public_path($quiz->photo));
                    }
                    $photo = $request->file('photo');
                    $photoName = time() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('quiz'), $photoName);
                    $quiz->update([
                        'title' => $request->title,
                        'photo' => 'quiz/' . $photoName,
                        'description' => $request->description,
                    ]);
                } else {
                    $quiz->update([
                        'title' => $request->title,
                        'description' => $request->description,
                    ]);
                }
                return ResponseFormatter::success($quiz, 'Quiz successfully updated');
            } catch (Exception $th) {
                return ResponseFormatter::error($th->getMessage());
            }
        } else {
            return ResponseFormatter::error('You are not allowed to do this action.');
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if($user->role->id == 3) {
            try {
                $quiz = Quiz::find($id);
                // delete old photo
                if (file_exists(public_path($quiz->photo))) {
                    unlink(public_path($quiz->photo));
                }
                $quiz->delete();
                return ResponseFormatter::success('Quiz successfully deleted');
            } catch (Exception $th) {
                return ResponseFormatter::error($th->getMessage());
            }
        } else {
            return ResponseFormatter::error('You are not allowed to do this action.');
        }
    }
}
