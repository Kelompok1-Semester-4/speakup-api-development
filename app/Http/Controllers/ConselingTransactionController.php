<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\ConselingTransaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConselingTransactionController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $conseling_transaction = ConselingTransaction::where('conselor_id', $user->id)->get();
        return ResponseFormatter::success($conseling_transaction);
    }

    public function getUserConselingTransaction()
    {
        $user = Auth::user();
        if ($user->role->id == 1) {
            $conseling_transaction = ConselingTransaction::with(['user', 'conselor'])->where('user_id', $user->id)->get();
            return ResponseFormatter::success($conseling_transaction);
        } else if ($user->role->id == 2) {
            $conseling_transaction = ConselingTransaction::with(['user','conselor'])->where('conselor_id', $user->id)->get();
            return ResponseFormatter::success($conseling_transaction);
        }
    }

    public function store(Request $request)
    {
        $user = $request->user_id;
        $conselor = Auth::user();
        $price = $request->price;
        $pay_status = 'pending';
        $conseling_status = 'pending';
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $conseling_transaction = ConselingTransaction::create([
            'user_id' => $user,
            'conselor_id' => $conselor->id,
            'price' => $price,
            'pay_status' => $pay_status,
            'conseling_status' => $conseling_status,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);
        return ResponseFormatter::success($conseling_transaction, 'Conseling transaction created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = $request->user_id;
        $conselor = $request->user();
        $price = $request->price;
        $pay_status = $request->pay_status;
        $conseling_status = $request->conseling_status;
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $conseling_transaction = ConselingTransaction::find($id);
        $conseling_transaction->update([
            'user_id' => $user,
            'conselor_id' => $conselor->id,
            'price' => $price,
            'pay_status' => $pay_status,
            'conseling_status' => $conseling_status,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);
        return ResponseFormatter::success($conseling_transaction, 'Conseling transaction updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $conseling_transaction = ConselingTransaction::find($id);
        $conseling_transaction->delete();
        return ResponseFormatter::success($conseling_transaction, 'Conseling transaction deleted successfully');
    }
}
