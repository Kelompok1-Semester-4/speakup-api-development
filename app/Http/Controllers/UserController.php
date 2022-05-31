<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\DetailUser;
use App\Models\Education;
use App\Models\User;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $role_id = $request->input('role_id');

        if ($id) {
            return ResponseFormatter::success(User::find($id));
        }

        if ($role_id) {
            // get detail user data by role_id
            return User::with('detailUser')->where('role_id', $role_id)->get();
        }
    }

    public function updateVerification(Request $request, $id)
    {
        try {
            DetailUser::where('user_id', $id)->update([
                'is_verified' => 1
            ]);
            return ResponseFormatter::success(DetailUser::find($id));
        } catch (Exception $th) {
            return ResponseFormatter::error([
                'message' => $th->getMessage(),
                'code' => $th->getCode()
            ]);
        }
    }

    public function detailConselor($id)
    {
        $user = DetailUser::with('education')->find($id);
        return ResponseFormatter::success($user);
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'gender' => 'required',
                'birth' => 'required',
                'phone' => 'required|string',
                'address' => 'required',
                'email' => 'required|unique:users',
                'password' => ['required', new Password],
                'role_id' => 'required|integer',
            ]);

            // parse to integer
            $request->role_id = intval($request->role_id); 

            if ($request->role_id == 1) {
                User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => $request->role_id,
                ]);
                $user = User::with('detailUser')->where('email', $request->email)->first();
                $token_result = $user->createToken('Personal Access Token')->plainTextToken;
                // insert data to detail_user table
                DetailUser::create(array_merge($request->all(), [
                    'user_id' => $user->id,
                ]));

                $detailUser = DetailUser::where('user_id', $user->id)->first();

                // show json api register success
                return ResponseFormatter::success([
                    'token' => $token_result,
                    'user' => $user,
                    'token_type' => 'Bearer',
                ], 'Register Success');
            }

            // insert data to education table
            if ($request->role_id == 2) {
                $request->validate([
                    'level' => 'required',
                    'institution' => 'required',
                    'institution_address' => 'required',
                    'major' => 'required',
                    'study_field' => 'required',
                    'graduation_year' => 'required',
                    'gpa' => 'required',
                    // file must be pdf or word
                    'file_url' => 'required|mimes:pdf,doc,docx',
                ]);

                // check if there is a file_url
                if ($request->hasFile('file_url')) {
                    $file = $request->file('file_url');
                    $fileName = $file->getClientOriginalName();
                    // generete random name
                    $fileName = uniqid() . '_' . trim($fileName);
                    $file->move(public_path('education'), $fileName);

                    User::create([
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role_id' => $request->role_id,
                    ]);
                    $user = User::with('detailUser')->where('email', $request->email)->first();
                    $token_result = $user->createToken('Personal Access Token')->plainTextToken;
                    // insert data to detail_user table
                    DetailUser::create(array_merge($request->all(), [
                        'user_id' => $user->id,
                    ]));

                    $detailUser = DetailUser::where('user_id', $user->id)->first();

                    Education::create(array_merge($request->all(), [
                        'detail_user_id' => $detailUser->id,
                        'file_url' => 'education/' . $fileName,
                    ]));
                    // show json api register success
                    return ResponseFormatter::success([
                        'token' => $token_result,
                        'user' => $user,
                        'token_type' => 'Bearer',
                    ], 'Register Success');
                }
            }

            if($request->role_id == 3) {
                User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => $request->role_id,
                ]);

                $user = User::with('detailUser')->where('email', $request->email)->first();
                $token_result = $user->createToken('Personal Access Token')->plainTextToken;
                // insert data to detail_user table
                DetailUser::create(array_merge($request->all(), [
                    'user_id' => $user->id,
                ]));

                $detailUser = DetailUser::where('user_id', $user->id)->first();

                // show json api register success
                return ResponseFormatter::success([
                    'token' => $token_result,
                    'user' => $user,
                    'token_type' => 'Bearer',
                ], 'Register Success');
            }
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Register Failed');
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required',
                'password' => ['required', new Password, 'min:6'],
            ]);

            $user = User::where('email', $request->email)->first();
            $detailUser = DetailUser::where('user_id', $user->id)->first();

            if (!password_verify($request->password, $user->password)) {
                return ResponseFormatter::error('Password is incorrect', 'Login Failed');
            }

            // set cookie
            $token_result = $user->createToken('Personal Access Token')->plainTextToken;
            return ResponseFormatter::success([
                'token' => $token_result,
                'user' => $user,
                'token_type' => 'Bearer',
                'detail_user' => $detailUser,
            ], 'Login Success')->withCookie('jwt', $token_result, 60 * 24 * 30);
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Login Failed');
        }
    }

    public function fetch(Request $request)
    {
        $detailUser = DetailUser::where('user_id', Auth::user()->id)->first();
        return ResponseFormatter::success(
            [
                'user' => $request->user(),
                'detailUser' => $detailUser,
            ],
            'Fetch Success'
        );
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ResponseFormatter::success('Logout Success');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        try {
            $detailUser = DetailUser::where('user_id', $user->id)->first();
            if($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = $file->getClientOriginalName();
                // generete random name
                $fileName = uniqid() . '_' . trim($fileName);
                $file->move(public_path('users'), $fileName);
                $detailUser->update(array_merge($request->all(), [
                    'photo' => 'users/' . $fileName,
                ]));
            } else {
                $detailUser->update($request->all());
            }
            return ResponseFormatter::success([
                'user' => $user,
                'detailUser' => $detailUser,
            ], 'Update Success');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Update Failed');
        }
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if($user->role->id == 3) {
            $user = User::find($id);
            $user->delete();
            return ResponseFormatter::success('Delete Success');
        } else {
            return ResponseFormatter::error('You are not allowed to delete this user', 'Delete Failed');
        }
    }
}
