<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tanggapan;
use JWTAuth;
use Carbon\Carbon;

class TanggapanController extends Controller
{
    public $response;
    public $user;
    public function __construct(){
        $this->response = new ResponseHelper();

        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'id_pengaduan' => 'required',
			'tanggapan' => 'required|string'
		]);

		if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
		}

		$tanggapan =  Tanggapan::where('id_pengaduan', $request->id_pengaduan)->first();
        
        //jika belum ada tanggapan brarti insert data baru
        if($tanggapan == NULL){
            $tanggapan = new Tanggapan();
        }
		
		$tanggapan->id_pengaduan   = $request->id_pengaduan;
		$tanggapan->tgl_tanggapan  = Carbon::now();
		$tanggapan->tanggapan      = $request->tanggapan;
		$tanggapan->id_petugas     = $this->user->id; //ambil id_petugas dari JWT token yang sedang aktif
		$tanggapan->save();

        return $this->response->successResponse('Data tanggapan berhasil dikirim');
    }
}
