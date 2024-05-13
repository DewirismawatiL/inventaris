<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use App\Models\StuffStock;
use App\Models\Lending;

class LendingController extends Controller
{
    public function __construct()
{
    $this->middleware('auth:api');
}
public function store(Request $request)
{
    try{
        $this->validate($request, [
            'stuff_id' => 'required',
            'date_time' => 'required',
            'name' => 'required',
            'total_stuff' => 'required',
        ]);
        //user_id tidak masuk ke validiasi karena value nya bukan bersumber dr luar)
        //cek total_variabel stuff terkait

        $totalAvailable = StuffStock::where('stuff_id', $request->stuff_id)->value('total_available');
        //mengambil satu kolom dari tabel total available
        //first ngambil smua data


        if(is_null($totalAvailable)) {
            return Apiformatter::sendResponse(400, 'bad request', 'Belum ada data inbound!');

        }elseif ((int)$request->total_stuff > (int)$totalAvailable) {
            return Apiformatter::sendResponse(400, 'bad request', 'stok tidak tersedia!');
        }else{
            $lending = Lending::create([
                'stuff_id' => $request->stuff_id,
                //dari kolom database //dari tanda panah dari validate yang berasal dari postman payload
                'date_time'=>$request->date_time,
                'name'=> $request->name,
                'notes' => $request->notes ? $request->notes : '-',
                'total_stuff' => $request->total_stuff,
                'user_id' => auth()->user()->id,
            ]);

            $totalAvailableNow = (int)$totalAvailable - (int)$request->total_stuff;
            $stuffStock = StuffStock::where('stuff_id', $request->stuff_id)->update([ 'total_available' => $totalAvailableNow]);

            $dataLending = Lending::where('id', $lending['id'])->with('user','stuff','stuff.stuffStock')->first();
            //user ga pake s karena d samain di function nya
            //stuff.stuffstock manggil relasi dalam relasi

            return Apiformatter::sendResponse(200, 'success', $dataLending);
        }

        }catch (\Exception $err) {
            return Apiformatter::sendResponse(400,'bad request', $err->getMessage());
        }
    }
    public function index()
    {
        try{
            //with: menertaakan data dari relas , isi di width disamakan dengan nama function relasi d model :: nya
            $data = Lending::with('stuff','user','restoration')->get();
            //mengambil semua data lending
            return ApiFormatter::sendResponse(200, 'success',$data);
        }catch (\Exception $err) {
            return ApiFormatter::sendResponse(400,'bad request', $err->getMessage());
        }
    }
    public function destroy($id)
{
    try {
        $lending = Lending::findOrFail($id);

        // Periksa apakah peminjaman memiliki proses pengembalian
        if ($lending->restoration()->exists()) {
            return ApiFormatter::sendResponse(400, 'bad request', 'Peminjaman tidak dapat dibatalkan karena sudah ada proses pengembalian');
        }

        // Ambil jumlah barang yang dipinjam
        $total_stuff = $lending->total_stuff;

        // Kembalikan jumlah barang yang dipinjam ke total_available pada stuff_stocks
        $stuff_stock = StuffStock::where('stuff_id', $lending->stuff_id)->first();
        $stuff_stock->total_available += $total_stuff;
        $stuff_stock->save();

        $lending->delete();

        return ApiFormatter::sendResponse(200, 'success', 'Berhasil menghapus data pemnjaman');
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
}
public function show($id)
    {
        try{
            $data = Lending::where('id', $id)->with('user', 'restoration', 'restoration.user', 'stuff', 'stuff.stuffStock')->first();

            return ApiFormatter::sendResponse(200, 'success', $data);
        }catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    }