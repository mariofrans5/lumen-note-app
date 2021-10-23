<?php

namespace App\Helper;
use App\Models\User;
use \Firebase\JWT\JWT;

class JsonWebToken
{
    protected $response;
    protected $key;
    protected $alg;
    protected $public_key;
    protected $private_key;

    public function __construct()
    {
        $this->response = [
            "err" => true,
            "msg" => "Request Not Processed",
            "data" => [],
            "code" => 422,
        ];
        $this->key = env('FIREBASE_JWT_KEY');
        $this->alg = env('FIREBASE_JWT_ALG');

    }

    public function login($email,$password)
    {

        if(!$email || !$password){
            throw new \Exception("Email and Password Required", 1);
            
        }

        $user = User::select(['user_id','email','password'])->where(['email'=>$email])->get()->first();
        if(!$user){
            throw new \Exception("User not Exist", 1);
        }
        
        if(! app('hash')->check($password ,$user->password) ){
            throw new \Exception("Not Authorized!!", 1);
        }

        $start = date('Y-m-d H:i:s');
        $end = date('Y-m-d H:i:s', strtotime('+60 minutes', strtotime($start))); 
        $iat = strtotime($start);
        $exp = strtotime($end);

        $payload = array(
            "user_id"=>$user->user_id,
            "user_email"=>$user->email,
            "iat" => $iat,
            "exp" => $exp
        );

        $encode = JWT::encode($payload, $this->key, $this->alg);
        if(!$encode){
            throw new \Exception("Fail Encode", 1); 
        }

        $token = "Bearer ".$encode;
        return [
            "token" =>$token,
            "end" =>$end,
        ];
    }
    
    public function decode($request,$token)
    {
        try {
            JWT::$leeway = 60;
            $decode = JWT::decode($token, $this->key, [$this->alg]);
            $request->merge([
                'user_id' => $decode->user_id,
                'user_email' => $decode->user_email,
            ]);
            return true; 

        } catch (\Exception $e) {
            return false; 
        }
    }
}
