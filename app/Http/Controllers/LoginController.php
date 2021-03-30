<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public $response;
    public function __construct(){
        $this->response = new ResponseHelper();
    }
    
    public function login(Request $request){
		$credentials = $request->only('username', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)){
                return $this->response->errorResponse('Invalid username and password');
			}
		} catch(JWTException $e){
            return $this->response->errorResponse('Generate Token Failed');
		}

        $data = [
			'token' => $token,
			'user'  => JWTAuth::user()
		];
        return $this->response->successResponseData('Authentication success', $data);
	}

    public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'nik' => 'required|string|max:20',
			'nama' => 'required|string|max:255',
			'username' => 'required|string|max:50|unique:Users',
			'password' => 'required|string|min:6',
			'telp' => 'required|string|min:10',
		]);

		if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

		$user = new User();
		$user->nik 	= $request->nik;
		$user->nama 	= $request->nama;
		$user->username = $request->username;
		$user->telp 	= $request->telp;
		$user->level 	= 'masyarakat';
		$user->password = Hash::make($request->password);
		$user->save();

		$token = JWTAuth::fromUser($user);

        $data = User::where('username','=', $request->username)->first();
        return $this->response->successResponseData('Data masyarakat berhasil ditambahkan', $data);
	}

	public function loginCheck(){
		try {
			if(!$user = JWTAuth::parseToken()->authenticate()){
				return $this->response->errorResponse('Invalid token!');
			}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
			return $this->response->errorResponse('Token expired!');
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
			return $this->response->errorResponse('Invalid token!');
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e){
			return $this->response->errorResponse('Token absent!');
		}

		return $this->response->successResponseData('Authentication success!', $user);
	}

    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return $this->response->successResponse('You are logged out');
        } else {
            return $this->response->errorResponse('Logged out failed');
        }
    }
}
