<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Stuff;
use Illuminate\Http\Request;


class StuffController extends Controller
{
    public function __construct()
{
    $this->middleware('auth:api');
}
    public function index()
    {
        try{
         // ambil data yang mau ditampilkan
         $data = Stuff::with('StuffStock')->get();

         return ApiFormatter::sendResponse(200, 'success', $data);
                }catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    public function store(Request $request)
    {
        try{
            // ambil data yang mau ditampilkan
            // validasi
            // 'nama_column' => 'validasi'
               $this->validate($request, [
                   'name'     => 'required|min:3',
                   'category' => 'required',
               ]);
   
               // proses tambah data
               //Namamodel::create([ 'column' => $request->name_or_key, ])
               $proses = Stuff::create([
                   'name' => $request->name,
                   'category' => $request->category,
               ]);
   
               //memunculkan data yg di proses masuk apa 
               if ($proses) {
                   return ApiFormatter::sendResponse(200, 'succes', $proses);
               } else {
                   return ApiFormatter::sendResponse(400, 'bad request', 'Gagal memproses tambah data stuff silahkan coba lagi');
                   //kalo catch ga bisa nangkep errornya jadi masuknya ke else
               }
           }catch (\Exception $err) {
               return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
           }
        }
    //$id : dari route yang ada {}
    public function show($id)
    {
        try{
            //ambil data yang mau ditampilkan
            $data = Stuff::where('id', $id)->first();
            //first(): kalau gada, tetep succes data kosong
            //firstOrfail():kalau gada , munculnya error
            //find(): mencari berdasarkan primary key
            //where():mencari column spesific tertentu

            return 
            ApiFormatter::sendResponse(200, 'success', $data);
        }catch (\Exception $err){
            return
            ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    //request:data yang dikirim
    //$id:data yang akan diupdate dr route{}
    public function update(Request $request, $id)
    {
        try{
            $this->validate($request,[
                'name'=>'required',
                'category'=>'required',
            ]);

            $checkProsess = Stuff::where('id', $id)->update([
                'name'=>$request->name,
                'category'=>$request->category,
            ]);

            if($checkProsess){
                //::create([]):menghasilkan data yang ditambah
                //::update([]):menghasilkan boolean,jd buat ambil data terbaru dicari lg
                $data = Stuff::where('id', $id)->first();
                return ApiFormatter::sendResponse(200,'success',$data);
            }
        }catch (\Exception $err){
            return ApiFormatter::sendResponse(400,'bad request',$err->getMessage());
        }
    }
    public function destroy($id)
{
    try {
        $checkProses = Stuff::findOrFail($id);

        if ($checkProses->inboundStuffs()->exists()) {
            return ApiFormatter::sendResponse(400, "bad request", "Tidak dapat menghapus data stuff, sudah terdapat data inbound");
        } 
        elseif ($checkProses->stuffStocks()->exists()) {
            return ApiFormatter::sendResponse(400, "bad request", "Tidak dapat menghapus data stuff, sudah terdapat data stuff stock");
        }
        elseif($checkProses->lendings()->exists()) {
            return ApiFormatter::sendResponse(400, "bad request", "Tidak dapat menghapus data stuff, sudah terdapat data lending");
        }
        elseif($checkProses->outboundStuffs()->exists()) {
            return ApiFormatter::sendResponse(400, "bad request", "Tidak dapat menghapus data stuff, sudah terdapat data outbound");
        }
        else {
            $checkProses->delete();
            return ApiFormatter::sendResponse(200, true, "berhasil hapus data barang dengan id $id", ['id' => $id]);
        }

    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
}
    public function trash()
        {
            try{
                //onlytrrashed():memanggil data sampah yang sudah dihapus/deleted_at nya terisi
                $data = Stuff::onlyTrashed()->get();
                //mengambil data sampah dengan onlytrashed
                return ApiFormatter::sendResponse(200, 'success', $data);
            }catch (\Exception $err){
                return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
            }
        }
    public function restore($id)
    {
    try {
        //restore: mengembalikan data yang dihapus /menghapus deleted_at nya
        $checkRestore = Stuff::onlyTrashed()->where('id',$id)->restore();
        if($checkRestore){
            $data = Stuff::where('id', $id)->first();
            return ApiFormatter::sendResponse(200,'success',$data);
        }
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400,'bad request' , $err->getMessage());
    }
}
public function permanentDelete($id)
{
    try {
        $checkPermanentDelete = Stuff::onlyTrashed()->where('id', $id)->forceDelete();
        if($checkPermanentDelete){
            return ApiFormatter::sendResponse(200,'success', 'Berhasil menghapus permanent data stuff!');
        }
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400,'bad request', $err->getMessage());
    }
}
}