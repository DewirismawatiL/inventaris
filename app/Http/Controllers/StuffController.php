<?php

namespace App\Http\Controllers;
use App\Helpers\ApiFormatter;
use App\Models\stuff;
use Exception;
use Illuminate\Http\Request;

class StuffController extends Controller
{
    public function index ()
    {
        try {
            //ambil data yang mau di tampilkan
            $data = Stuff::all()->toArray();
            
            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // validasi -> memastikan data lengkap
            $this->validate($request, [
                'name' => 'required|min:3',
                'category' => 'required',
            ]);

            $prosesData = Stuff::create([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            if($prosesData) {
                return ApiFormatter::sendResponse(200, 'succes', $prosesData);
            } else {
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal memproses tambah data stuff!
                silahkan coba lagi');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    //$id : dari route yang ada {}
    // show untuk menampilkan salah satu id
    public function show($id){
        try{ // dicoba dulu di try lalu si variable datanya itu nyari colom berdasarkan id terus di return lalu di kembalikan
            // kalo hasilnya 200 berarti sukses kalo 400 berarti error , get message buat ngasih tau errornya dimana
            $data = Stuff::where('id', $id)->first();
            // first() : (perbedaanya kalau gada, tetep succes tapi data kosong)
            // firstOrFail() : (perbedaanya kalau gada, di munculkannya sebagai error)
            // find() : (mencari berdasarkan (id)primary key)
            // where() : (mencari column spesific tertentu)

            return ApiFormatter::sendResponse(200, 'succes', $data);
        } catch (\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    // request data yang dikirim
    // $id : data yang akan di update, dari route {}
    public function update(Request $request, $id) //-> ngambil data yang di input // fungsi update ini digunakam untuk mengupdate data yang ada di staf
    // berdasarkan id nya
    {
        try { // dicoba validasi request name categori
            $this->validate($request, [
                'name' => 'required',
                'category' => 'required'
            ]);

            $checkProsess = Stuff::where('id', $id)->update([ // llau di cek atau proses
                'name' => $request->name,
                'category' => $request->category
            ]);

            if ($checkProsess){
                // ::create ([]) : menghasilkan data yang ditambah
                // ::update ([]) : menghasilkan boolean jadi buat ambil data terbaru di cari lagi
                $data = Stuff::find($id);
                return ApiFormatter::sendResponse(200, 'succes', $data);
            } else {
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal mengubah data!');
            }
        }catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function destroy($id){
        try {
            $checkProsess = Stuff::where ('id', $id)->delete();

            if ($checkProsess) {
                return ApiFormatter::sendResponse(200, 'success', 'Berhasil hapus data stuff!');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function trash()
    {
        try {
            // onlyTrashed(): memanggil data sampah/ yang sudah di hapus/ deleted_at nya terisi
            $data = Stuff::onlyTrashed()->get();

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bed request', $err->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            // restore : mengembalikan data yang dihapus/menghapus deleted_at mya
            $checkRestore = Stuff::onlyTrashed()->where('id', $id)->restore();

            if ($checkRestore) {
                $data = Stuff::where('id', $id)->first();
                
                return ApiFormatter::sendResponse(200, 'success', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function permanenDelete($id)
    {
        try {
            $checkPermanenDelete = Stuff::onlyTrashed()->where('id', $id)->forceDelete();

            if ($checkPermanenDelete) {
                return ApiFormatter::sendResponse(200, 'success', 'Berhasil menghapus permanent data stuff');
            }
        } catch (Exception $err) {
            return ApiFormatter::sendResponse(400 , 'bad request', $err->getMessage());
        }
    }
}
