<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pengaduan;
use JWTAuth;
use DB;

class PengaduanController extends Controller
{
    public $response;
    public $user;
    public function __construct(){
        $this->response = new ResponseHelper();

        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function getAllPengaduan($limit = NULL, $offset = NULL)
    {
        if($this->user->level == 'masyarakat'){
            $data["count"] = Pengaduan::where('id_user', '=', $this->user->id)->count();

            if($limit == NULL && $offset == NULL){
                $data["pengaduan"] = Pengaduan::where('id_user', '=', $this->user->id)->orderBy('tgl_pengaduan', 'desc')->with('kategori','tanggapan','user')->get();
            } else {
                $data["pengaduan"] = Pengaduan::where('id_user', '=', $this->user->id)->orderBy('tgl_pengaduan', 'desc')->with('kategori','tanggapan','user')->take($limit)->skip($offset)->get();
            }
        } else {
            $data["count"] = Pengaduan::count();

            if($limit == NULL && $offset == NULL){
                $data["pengaduan"] = Pengaduan::orderBy('tgl_pengaduan', 'desc')->with('kategori','tanggapan','user')->get();
            } else {
                $data["pengaduan"] = Pengaduan::orderBy('tgl_pengaduan', 'desc')->with('kategori','tanggapan','user')->take($limit)->skip($offset)->get();
            }
        }

        return $this->response->successData($data);
    }

    public function getById($id)
    {   
        $data["pengaduan"] = Pengaduan::where('id_pengaduan', $id)->with('kategori','tanggapan','user')->get();

        return $this->response->successData($data);
    }

    public function get($id_kategori){
        try{
              $data["count"] = Pengaduan::count();
              $wisata = array();
              foreach ( Wisata::where("jenis", $jenis)->get() as $p) {
                  $item = [
                      "id"          => $p->id,
                      "nama_wisata" => $p->nama_wisata,
                      "nama_daerah" => $p->nama_daerah,
                      "deskripsi"   => $p->deskripsi,
                      "jenis"    	  => $p->jenis,
                      "akses"    	  => $p->akses,
                      "foto"        => $p->foto,
                      "created_at"  => $p->created_at,
                      "updated_at"  => $p->updated_at
                  ];
  
                  array_push($wisata, $item);
              }
              $data["wisata"] = $wisata;
              $data["status"] = 1;
              return response($data);
  
          } catch(\Exception $e){
              return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
              ]);
            }
      }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'tgl_pengaduan' => 'required|string',
			'isi_laporan' => 'required|string',
			'id_kategori' => 'required',
			// 'foto' => 'required',
		]);

		if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

        $foto = rand().$request->file('foto')->getClientOriginalName();
        $request->file('foto')->move(base_path("./public/uploads"), $foto);

		$pengaduan = new Pengaduan();
		$pengaduan->id_user         = $this->user->id;
		$pengaduan->id_kategori     = $request->id_kategori;
		$pengaduan->tgl_pengaduan   = $request->tgl_pengaduan;
		$pengaduan->isi_laporan     = $request->isi_laporan;
        $pengaduan->foto            = $foto;
        $pengaduan->status          = 'terkirim';
		$pengaduan->save();

        $data = Pengaduan::where('id_pengaduan','=', $pengaduan->id)->first();
        return $this->response->successResponseData('Data pengaduan berhasil terkirim', $data);
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'id_pengaduan' => 'required',
			'status' => 'required|string',
		]);

		if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

		$pengaduan          = Pengaduan::where('id_pengaduan', $request->id_pengaduan)->first();
		$pengaduan->status  = $request->status;
		$pengaduan->save();

        return $this->response->successResponse('Status berhasil diubah');
    }
}
