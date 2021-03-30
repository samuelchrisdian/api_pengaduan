<?php
namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public $response;
    public function __construct(){
        $this->response = new ResponseHelper();
    }
    
    public function getAll($limit = NULL, $offset = NULL)
    {
        $data["count"] = Kategori::count();
        
        if($limit == NULL && $offset == NULL){
            $data["kategori"] = Kategori::get();
        } else {
            $data["kategori"] = Pengaduan::take($limit)->skip($offset)->get();
        }

        return $this->response->successData($data);
    }

    public function getById($id)
    {   
        $data["kategori"] = Kategori::where('id_kategori', $id)->get();

        return $this->response->successData($data);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'nama_kategori' => 'required|string'
		]);

		if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

		$kategori = new Kategori();
		$kategori->nama_kategori = $request->nama_kategori;
		$kategori->save();

        $data = Kategori::where('id_kategori','=', $kategori->id_kategori)->first();
        return $this->response->successResponseData('Data kategori berhasil ditambahkan', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
			'nama_kategori' => 'required|string'
		]);

		if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

		$user = Kategori::where('id_kategori', $id)->first();
		$user->nama_kategori = $request->nama_kategori;
		$user->save();

        return $this->response->successResponse('Data kategori berhasil diubah');
    }

    public function delete($id)
    {
        $delete = Kategori::where('id_kategori', $id)->delete();

        if($delete){
            return $this->response->successResponse('Data kategori berhasil dihapus');
        } else {
            return $this->response->errorResponse('Data kategori gagal dihapus');
        }
    }
}
