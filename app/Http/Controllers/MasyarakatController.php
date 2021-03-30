<?php
namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class MasyarakatController extends Controller
{
    public $response;
    public function __construct(){
        $this->response = new ResponseHelper();
    }

    public function getAll($limit = NULL, $offset = NULL)
    {
        $data["count"] = User::where('level','=','masyarakat')->count();
        
        if($limit == NULL && $offset == NULL){
            $data["user"] = User::where('level','=','masyarakat')->get();
        } else {
            $data["user"] = User::where('level','=','masyarakat')->take($limit)->skip($offset)->get();
        }

        return $this->response->successData($data);
    }

    public function getById($id)
    {   
        $data["user"] = User::where('id', $id)->get();

        return $this->response->successData($data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'nik' => 'required|numeric',
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
        $user->password = Hash::make($request->password);
		$user->telp 	= $request->telp;
		$user->level 	= 'masyarakat';
		$user->save();

        $data = User::where('username','=', $request->username)->first();
        return $this->response->successResponseData('Data masyarakat berhasil ditambahkan', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
			'nik' => 'required|numeric',
			'nama' => 'required|string|max:255',
			'password' => 'required|string|min:6',
			'telp' => 'required|string|min:10'
		]);

		if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

		$user = User::where('id', $id)->first();
		$user->nik 	= $request->nik;
		$user->nama 	= $request->nama;
		$user->username 	= $request->username;
        $user->password = Hash::make($request->password);
		$user->telp 	= $request->telp;
		$user->save();

        return $this->response->successResponse('Data masyarakat berhasil diubah');
    }

    public function delete($id)
    {
        $delete = User::where('id', $id)->delete();

        if($delete){
            return $this->response->successResponse('Data masyarakat berhasil dihapus');
        } else {
            return $this->response->errorResponse('Data masyarakat gagal dihapus');
        }
    }

    
}
