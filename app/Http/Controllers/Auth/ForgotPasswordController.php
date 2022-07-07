<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\User;
use Password;
use Illuminate\Http\Request;
use Auth;
use Response;
use Swift_TransportException;
use Exception;
use Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Validator;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

     /**
     * Forgot password
     * @param request
     * @return response
     */

    public function forgot(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
           
        ]);
        
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Email is not registered.'], 200);
        }
        $token = Str::random(60);
        $user['token'] = $token;
        // $user['is_verified'] = 0;
        $user->save();
        $email=$request->email;
        $url=$request->site_url;    
        Mail::to($email)->send(new ResetPassword($user->fullname, $token, $url));

        if(Mail::failures() != 0) {
            return response()->json([
                'status' => true,
                'message' => 'Reset password link sent on your email id.'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'There is some issue with email provider.'
        ], 200);
    }
        /**
     * Change password
     * @param request
     * @return response
     */
    public function updatePassword(Request $request,$token) {
        

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'confirm_password' => 'required|same:password'
           
        ]);
        
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $user = User::where('token',$token)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token.'
            ], 200);
        }
        if ($user) {
            // $user['is_verified'] = 0;
            $user['token'] = NULL;
            $user['password'] = Hash::make($request->password);
            $user->save();
            return response()->json([
            'status' => true,
            'message' => 'password has been changed.'
        ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.'
        ], 200);
    }
}
