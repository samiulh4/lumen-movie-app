<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\Users;
use App\Models\UsersAuthLog;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Traits\helperTrait;

class AuthenticationController extends Controller
{
    use helperTrait;

    public function __construct()
    {

    }

    public function sign_up(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'password' => 'required',
        ]);
        try {
            $user = new Users();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $password = $request->password;
            $user->password = Hash::make($password);
            $user->save();
            $user->created_by = $user->id;
            $user->updated_by = $user->id;
            if ($request->hasFile('avatar')) {
                $request_file = $request->file('avatar');
                $original_filename = $request->file('avatar')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './uploads/users/';
                if (!file_exists($destination_path)) {
                    mkdir($destination_path, 0777, true);
                }
                $file_save_as = 'USER_' . date('Ymd') . '_' . time() . '.' . $file_ext;
                if ($request_file->move($destination_path, $file_save_as)) {
                    $user->avatar = str_replace(".", "", $destination_path) . $file_save_as;
                }
            }
            $user->save();
            return response()->json([
                'message' => 'User Registration Successfully.',
                'responseStatus' => 1,
                'responseCode' => 201,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'responseStatus' => 0,
                'responseCode' => 409,
            ], 409);
        }
    }// end -:- sign_up()

    public function sign_in(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only(['email', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(
                [
                    'responseStatus' => 1,
                    'responseCode' => 401,
                    'message' => 'Unauthorized'
                ], 401);
        } else {
            $userAuthLog = new UsersAuthLog();
            $userAuthLog->sign_in_date = date('Y-m-d H:i:s');
            $userAuthLog->auth_token = $token;
            $userAuthLog->auth_token_type = 'bearer';
            $userAuthLog->created_by = Auth::user()->id;
            $userAuthLog->updated_by = Auth::user()->id;
            $userAuthLog->client_ip = $request->ip();
            $userAuthLog->save();
        }
        return $this->respondWithToken($token);
    }// end -:- sign_in()

    public function sign_out(Request $request)
    {
        try {

            $userAuthLog = UsersAuthLog::where('created_by', Auth::user()->id)->orderBy('id', 'desc')->first();
            $userAuthLog->sign_out_date = date('Y-m-d H:i:s');
            $userAuthLog->updated_by = Auth::user()->id;
            $userAuthLog->save();

            Auth::logout();

            return response()->json([
                'responseStatus' => 1,
                'responseCode' => 200,
                "message" => "User sign out successfully."
            ], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 500,
                    'message' => $e->getMessage(),
                ], 500);
        }
    }// end -:- sign_out()

    public function resetPassword(Request $request)
    {
        // Implement password reset logic here
    }

    public function sign_in_user()
    {
        try {
            $id = Auth::user()->id;
            $user = [];
            $user['id'] = $this->customEncode($id);
            $user['name'] = Auth::user()->name;
            $user['email'] = Auth::user()->email;
            $user['user_type'] = Auth::user()->user_type;
            $user['avatar'] =  Auth::user()->avatar;
            $user['gender'] = Auth::user()->gender;

            return response()->json([
                'responseStatus' => 1,
                'responseCode' => 200,
                "user" => $user,
                "message" => 'Authorized user found.'
            ], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 401,
                    'message' => $e->getMessage(),
                ], 401);
        }
    }// end -:- sign_in_user()

    public function token_refresh()
    {
        try {
            $token = JWTAuth::getToken();
            $newToken = JWTAuth::refresh($token);
            return response()->json(
                [
                    'responseStatus' => 1,
                    'responseCode' => 200,
                    'token' => $newToken,
                    'message' => 'Auth Token Generate Successfully.',
                ], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 401,
                    'message' => $e->getMessage(),
                ], 401);
        }
    }// end -:- apiTokenRefresh()

    private function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }// end -:- respondWithToken()

}// end -:- AuthenticationController
