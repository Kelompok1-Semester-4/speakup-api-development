<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\DetailQuiz;
use Exception;
use Illuminate\Http\Request;

class DetailQuizController extends Controller
{

    public function index(Request $request)
    {
        $id = $request->input('id');
        $detailQuiz = DetailQuiz::get();
        if ($id) {
            $detailQuiz = DetailQuiz::where('id', $id)->get();
        }

        return ResponseFormatter::success($detailQuiz);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if($user->role->id == 3) {
            try {
                $request->validate([
                    'quiz_id' => 'required|integer|exists:quiz,id',
                    'title' => 'required|string|max:255|unique:detail_quiz,title',
                    'question1' => 'nullable|string|max:255',
                    'question2' => 'nullable|string|max:255',
                    'question3' => 'nullable|string|max:255',
                    'question4' => 'nullable|string|max:255',
                ]);
    
    
                $detailQuiz = DetailQuiz::create([
                    'quiz_id' => $request->quiz_id,
                    'title' => $request->title,
                    'question1' => $request->question1,
                    'question2' => $request->question2,
                    'question3' => $request->question3,
                    'question4' => $request->question4,
                ]);
    
                return ResponseFormatter::success($detailQuiz, 'Detail Quiz successfully created');
            } catch (Exception $th) {
                return ResponseFormatter::error($th->getMessage(), 500);
            }
        } else {
            return ResponseFormatter::error('You are not allowed to do this action.');
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        if($user->role->id == 3) {
            try {
                $request->validate([
                    'quiz_id' => 'required|integer|exists:quiz,id',
                    'title' => 'required|string|max:255',
                    'question1' => 'nullable|string|max:255',
                    'question2' => 'nullable|string|max:255',
                    'question3' => 'nullable|string|max:255',
                    'question4' => 'nullable|string|max:255',
                ]);
    
                $detailQuiz = DetailQuiz::findOrFail($id);
    
                $detailQuiz->update([
                    'quiz_id' => $request->quiz_id,
                    'title' => $request->title,
                    'question1' => $request->question1,
                    'question2' => $request->question2,
                    'question3' => $request->question3,
                    'question4' => $request->question4,
                ]);
    
                return ResponseFormatter::success($detailQuiz, 'Detail Quiz successfully updated');
            } catch (Exception $th) {
                return ResponseFormatter::error($th->getMessage(), 500);
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
                $detailQuiz = DetailQuiz::findOrFail($id);
                $detailQuiz->delete();
    
                return ResponseFormatter::success($detailQuiz, 'Detail Quiz successfully deleted');
            } catch (Exception $th) {
                return ResponseFormatter::error($th->getMessage(), 500);
            }
        } else {
            return ResponseFormatter::error('You are not allowed to do this action.');
        }
    }
}
