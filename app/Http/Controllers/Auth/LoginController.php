<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
     protected $user;

    public function __construct(){

    }

    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'user']);
            
            $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
        //     $response_data['id'] = $user->id;
        //     $response_data['role'] = $user->role;
        //     $response_data['Status'] = 200;
        //     $response_data['Message'] = 'Successful Login';
        //  //   'token' => $token->accessToken,
        //     $success =  $response_data;
        //     $success['token'] = 
        //      $user->createToken($user->fullname . '-' . now())->accessToken;
             $token = $user->createToken('MyApp',['user'])->accessToken;
             $ext=($user->img_file)?$user->img_file:$user->emp_id.'.jpg';
		    $path = public_path('storage/picture/'.$ext);

            if(file_exists($path)){
                $user->profile_pic =env('APP_URL').'storage/picture/'.$ext;
            } else {
                $user->profile_pic =env('APP_URL').'storage/picture/avatar.png';
            }
           //  $duo_token = DuoUtil::signRequest(config('duo_values.i_key'), config('duo_values.s_key'), config('duo_values.a_key'), $user->username);
             return response()->json(array(
                 'Status' => 200,
                 'id' => $user->id,
                 'username'=> $user->email,
                 'fullname'=> $user->fullname,
                 'profile_pic' => $user->profile_pic,
                 'firstName'=>  'test',
                 'lastName'=> 'test',
                 'role' => 'User',
                 'token' => $token,  
                 'Message' => 'Successful Login'
             ));
            // return response()->json($success, 200);
        }else{ 
            return response()->json(['error' => ['Email and Password are Wrong.']], 200);
        }
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('admin')->attempt(['email' => request('email'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'admin']);
            $admin = Admin::find(auth()->guard('admin')->user()->id);
            // $response_data['id'] = $admin->id;
            // $response_data['role'] = $admin->role;
            // // $success =  $response_data;
            // $success['token'] =  $admin->createToken('MyApp',['admin'])->accessToken; 

            $token =  $admin->createToken('MyApp',['admin'])->accessToken; 
            //  $duo_token = DuoUtil::signRequest(config('duo_values.i_key'), config('duo_values.s_key'), config('duo_values.a_key'), $user->username);
              return response()->json(array(
                  'Status' => 200,
                  'id' => $admin->id,
                  'username'=> $admin->email,
                  'fullname'=> $admin->name,
                  'profile_pic' => env('APP_URL').'storage/picture/avatar.png',
                  'firstName'=>  'test',
                  'lastName'=> 'test',
                  'role' => 'Admin',
                  'role_number' => $admin->role,
                  'token' =>  $token,  
                  'Message' => 'Successful Login'
              ));
        }else{ 
            return response()->json(['error' => ['Email and Password are Wrong.']], 200);
        }
    }
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function adminUserLogin(Request $request)
    {

     //  dd(now()->addMinute(1));
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        // if($validator->fails()){
        //     return response()->json(['error' => $validator->errors()->all()]);
        // }
        $user = User::where('email',$request->email)->first();
        if($user ){
            $ext=($user->img_file)?$user->img_file:$user->emp_id.'.jpg';
		    $path = public_path('storage/picture/'.$ext);

            if(file_exists($path)){
                $user->profile_pic =env('APP_URL').'storage/picture/'.$ext;
            } else {
                $user->profile_pic =env('APP_URL').'storage/picture/avatar.png';
            }
            config(['auth.guards.api.provider' => 'user']);
            
          //  $user = User::select('users.*')->find(auth()->guard('user')->user()->id);
        //     $response_data['id'] = $user->id;
        //     $response_data['role'] = $user->role;
        //     $response_data['Status'] = 200;
        //     $response_data['Message'] = 'Successful Login';
        //  //   'token' => $token->accessToken,
        //     $success =  $response_data;
        //     $success['token'] = 
        //      $user->createToken($user->fullname . '-' . now())->accessToken;
             $token = $user->createToken('MyApp',['user'])->accessToken;
           //  $duo_token = DuoUtil::signRequest(config('duo_values.i_key'), config('duo_values.s_key'), config('duo_values.a_key'), $user->username);
             return response()->json(array(
                 'Status' => 200,
                 'id' => $user->id,
                 'username'=> $user->email,
                 'fullname'=> $user->fullname,
                 'firstName'=>  'test',
                 'lastName'=> 'test',
                 'profile_pic' => $user->profile_pic,
                 'role' => 'User',
                 'token' => $token,  
                 'Message' => 'Successful Login'
             ));
            // return response()->json($success, 200);
        }else{ 
            return response()->json(['error' => ['Email is not valid.']], 200);
        }
    }

        
}
