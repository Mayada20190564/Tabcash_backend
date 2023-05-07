<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Nexmo\Laravel\Facade\Nexmo;
use App\User ;
use App\Otp ;
use Carbon\Carbon;
class authController extends Controller
{
     // //////////////// Register 
     public function register(Request $req){
        $basic  = new \Vonage\Client\Credentials\Basic("6f37ae83", "i0505XJz2HWR542U");
        $client = new \Vonage\Client($basic);
        // validation
        $fields = $req->validate([
            "name" => "required|string",
            "email" => "required|email|string",
            "mobile" => "required|string|unique:users,mobile|max:11",
            "password" => "required|string|confirmed", 
        ]);
        $code = mt_rand(1000 , 9999);
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS('2'.$fields['mobile'], 'TabCash', 'Your Verification Code is'.$code)
        );
        
        // $message = $response->current();
        
        // if ($message->getStatus() == 0) {
        //     echo "The message was sent successfully\n";
        // } else {
        //     echo "The message failed with status: " . $message->getStatus() . "\n";
        // }
       
        // create user
        $user = User::create([
            "name" => $fields['name'],
            "email" => $fields['email'],
            "mobile" => $fields['mobile'],
            "password" => bcrypt($fields['password']),
        ]);
        $response = [
            'user' => $user,
            'code' => $code
        ];
        
        return response($response , 201);
    }
    // //////////////// code registeration
    public function otp(Request $req){
         // Validation
         $fields = $req->validate([
            'mobile' => "required|string|max:11",
            'code' => "required|string|max:4"
        ]);
        $user = User::where('mobile' , $fields['mobile'])->first();
        $otp = Otp::create([
            'user_id' => $user->id,
            'code' => $fields['code'],
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);
        // create Tokens
        $token = $user->createToken('Token')->plainTextToken;
        // Response
        $response = [
            'user' => $user,
            'Token' => $token,
        ];
        return response($response , 201);

    }
    // public function 
     // ////////////////// Logout
     public function logout(Request $req){
        auth()->user()->tokens()->delete();
    }
    // ////////////////// Login
    public function login(Request $req){
        $basic  = new \Vonage\Client\Credentials\Basic("6f37ae83", "i0505XJz2HWR542U");
        $client = new \Vonage\Client($basic);
        // Validation
        $fields = $req->validate([
            'mobile' => "required|string|max:11",
            'password' => "required|string"
        ]);
        // get user
        $user = User::where('mobile' , $fields['mobile'])->first();
        // check e-mail is correct
        if(!$user){
            return [
                'message' => "Mobile number is not exist"
            ];
        }
        // check password is correct
        if(!Hash::check($fields['password'], $user->pasword)){
            return [
                'message' => "passowrd is not correct"
            ];
        }
        $code = mt_rand(1000 , 9999);
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS('2'.$fields['mobile'], 'TabCash', 'Your Verification Code is'.$code)
        );
        // return response
        $response = [
            'user' => $user , 
            'code' => $code
        ];
        return response($response , 201);
    }
}
