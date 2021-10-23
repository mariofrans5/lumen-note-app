<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\JsonWebToken;

class JWT_Auth
{

    protected $JsonWebToken;
    protected $response;

    public function __construct(JsonWebToken $JsonWebToken)
    {
        $this->response = [
            "err" => true,
            "msg" => "Not Authorized",
            "data" => [],
            "code" => 401,
        ];
        $this->JsonWebToken = $JsonWebToken;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('authorization');
        if(!$token){
            return response()->json($this->response,$this->response['code']); 
        }
        $token = str_replace('Bearer ','',$token);
        $allowed = $this->JsonWebToken->decode($request,$token);

        if(!$allowed){
            return response()->json($this->response,$this->response['code']); 

        }
        return $next($request);
    }
}
