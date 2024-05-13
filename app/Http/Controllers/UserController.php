<?php

namespace App\Http\Controllers;
use App\Helpers\ApiFormatter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function __construct() //construct bakal ngejalanin meskipun tidak dipanggil
    //fungsi nya utnuk mengindinisikan middlewernya
    {
        // middlewer : membatasi, nama nama function yang hanya bisa di akses setelah login
        $this->middleware('auth:api', ['except' => ['login', 'logout']]);//except : fungsi mn yg blh diakses sebelum suatu tindakan (login)
    }
    
    public function index()
    {
        try {
            $data = User::all()->toArray();

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required',
                'username' => 'required|min:4',
                'role' => 'required',
            ]);

            $prosesData = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'role' => $request->role,
                'password' => Crypt::encrypt($request->password),
            ]);

            if ($prosesData) {
                return ApiFormatter::sendResponse(200, 'success', $prosesData);
            } else {
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal memproses tambah data user! Silahkan coba lagi');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function show($id){
        try{
            $data = User::where('id', $id)->first();

            return ApiFormatter::sendResponse(200, 'succes', $data);
        } catch (\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $checkProcess = User::where('id', $id)->delete();

            if ($checkProcess) {
                return ApiFormatter::sendResponse(200, 'success', 'Berhasil hapus data user!');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function trash()
    {
        try {
            $data = User::onlyTrashed()->get();
            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $checkRestore = User::onlyTrashed()->where('id', $id)->restore();

            if ($checkRestore) {
                $data = User::where('id', $id)->first();
                return ApiFormatter::sendResponse(200, 'success', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function permanenDelete($id)
    {
        try {
            $checkPermanentDelete = User::onlyTrashed()->where('id', $id)->forceDelete();

            if ($checkPermanentDelete) {
                return ApiFormatter::sendResponse(200, 'success', 'Berhasil menghapus data user secara permanen');
            }
        } catch (Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
}
