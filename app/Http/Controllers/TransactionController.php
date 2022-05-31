<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Course;
use App\Models\DetailTransaction;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        try {

            $detail_transactions = DetailTransaction::with('user', 'course')
                ->where('user_id', $user->id)
                ->get();
            $transaction = Transaction::where('user_id', $user->id)->get();
            return ResponseFormatter::success([
                'detail_transactions' => $detail_transactions,
                'transaction' => $transaction
            ]);
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'course_id' => 'required',
                'status' => 'required',
                'total_price' => 'required',
            ]);

            $user = Auth::user();
            DetailTransaction::create([
                'user_id' => $user->id,
                'course_id' => $request->course_id,
                'total_price' => $request->total_price,
            ]);

            $detail_transaction = DetailTransaction::with('transaction')->where('user_id', $user->id)->first();

            Transaction::create([
                'user_id' => $user->id,
                'detail_transaction_id' => $detail_transaction->id,
                'status' => 'PENDING',
            ]);

            $transaction = Transaction::with('detail_transaction')->where('user_id', $user->id)->get();

            return ResponseFormatter::success($transaction, 'Detail Transaction created successfully');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $transaction = Transaction::find($id);

            if ($transaction->user_id != $user->id) {
                return ResponseFormatter::error('You are not authorized to update this transaction');
            }

            $request->validate([
                'status' => 'required',
            ]);

            $transaction->update([
                'status' => $request->status,
            ]);

            return ResponseFormatter::success($transaction, 'Transaction updated successfully');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function conselorTransaction()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $detail_transaction = DetailTransaction::with([
            'user.detailUser',
            'course',
        ])->whereHas('course', function ($query) use ($user_id) {
            $query->where('detail_user_id', $user_id);
        })->get();

        return ResponseFormatter::success($detail_transaction);
    }

    public function detailConselorTransaction($id)
    {
        $transaction = Transaction::with([
            'detail_transaction.user.detailUser',
            'detail_transaction.course',
        ])->where('detail_transaction_id', $id)->first();
        return ResponseFormatter::success($transaction);
    }

    public function updateTransaction($id)
    {
        $transaction = Transaction::find($id);
        $transaction->update([
            'status' => 'SUCCESS',
        ]);
        return ResponseFormatter::success($transaction);
    }
}
