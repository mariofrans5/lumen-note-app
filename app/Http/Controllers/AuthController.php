<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helper\JsonWebToken;
class AuthController extends Controller
{
    
    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        try {
            
            if(User::where(['email'=>$request->input('email')])->first()){
                throw new \Exception("User Already Exist", 1);
            }
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();
            //return successful response
            $this->response = [
                "err" => false,
                "msg" => "Success Created",
                "data" => ['user' => $user],
                "code" => 201,
            ];
            return response()->json($this->response, $this->response['code']);

        } catch (\Exception $e) {
            //return error message
            $this->response = [
                "err" => true,
                "msg" => "User Registration Failed!",
                "data" => $e->getMessage(),
                "code" => 409,
            ];
            return response()->json($this->response, $this->response['code']);
        }

    }

    public function login(Request $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $JsonWebToken = new JsonWebToken;
            $result_login = $JsonWebToken->login($email,$password);
            
            $this->response = [
                "err" => false,
                "msg" => "Success Login",
                "data" => [
                    'token' => $result_login['token'],
                    'end' => $result_login['end'],
                ],
                "code" => 200,
            ];
            return response()->json($this->response,$this->response['code']);

        } catch (\Exception $e) {
            //return error message
            $this->response = [
                "err" => true,
                "msg" => "Failed",
                "data" => $e->getMessage(),
                "code" => 409,
            ];
            return response()->json($this->response,$this->response['code']);
        }

    }

}