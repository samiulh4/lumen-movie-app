<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\Users;
use App\Models\UsersAuthLog;
use App\Traits\helperTrait;

class UserController extends Controller
{
    use helperTrait;

    public function __construct()
    {

    }

    public function update(Request $request, $id)
    {
        if (empty($id)) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 404,
                    'message' => 'The server cannot find the requested resource.',
                ], 404);
        }
        $decode_id = $this->customDecode($id);
        $this->validate($request, [
            'name' => 'required|string',
            'gender' => 'required|string',
            //'avatar' => 'file|required|mimes:jpg,png',
            'avatar' => 'file|required',
        ]);
        try {
            $user = Users::where('id', $decode_id)->first();
            $user->name = $request->name;
            $user->gender = $request->gender;
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
                    //dd(str_replace(".","",$destination_path).$file_save_as);
                }
            }
            $user->updated_at = date('Y-m-d H:i:s');
            $user->updated_by = Auth::user()->id;
            $user->save();

            $updatedDate = [];
            $updatedDate['id'] = $this->customEncode($user->id);
            $updatedDate['name'] = $user->name;
            $updatedDate['email'] = $user->email;
            $updatedDate['user_type'] = $user->user_type;
            $updatedDate['avatar'] = $user->avatar;
            $updatedDate['gender'] = $user->gender;

            return response()->json(
                [
                    'responseStatus' => 1,
                    'responseCode' => 200,
                    'message' => 'User Information Updated Successfully.',
                    'user' => $updatedDate,
                ], 200);
        } catch (Exception $e) {
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 500,
                    'message' => $e->getMessage(),
                ], 500);
        }
    }// end -:- update

    public function getUserDataById( $id)
    {
        //header('Access-Control-Allow-Origin: *');
        try{
            $decode_id = $this->customDecode($id);
            $userData = Users::where('id',  $decode_id)->first();
            $user = [];
            $user['id'] = $this->customEncode($userData->id);
            $user['name'] = $userData->name;
            $user['email'] = $userData->email;
            $user['user_type'] = $userData->user_type;
            $user['avatar'] = $userData->avatar;
            $user['gender'] = $userData->gender;
            return response()->json([
                'responseStatus' => 1,
                'responseCode' => 200,
                "user" => $user,
                "message" => 'User is found by '.$id
            ], 200);
        }catch (\Exception $e){
            return response()->json(
                [
                    'responseStatus' => 0,
                    'responseCode' => 401,
                    'message' => $e->getMessage(),
                ], 401);
        }
    }// end -:- getUserDataById()
}// end -:- UserController
