<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\ApiFormatter;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;


class AuthController extends Controller
{
    public function __construct() //construct bakal ngejalanin meskipun tidak dipanggil
    //fungsi nya utnuk mengindinisikan middlewernya
    {
        // middlewer : membatasi, nama nama function yang hanya bisa di akses setelah login
        $this->middleware('auth:api', ['except' => ['login', 'logout']]);//except : fungsi mn yg blh diakses sebelum suatu tindakan (login)
    }
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
	    $this->validate($request, [
            'email'=> 'required',
            'password'=> 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)){ // kenapa ada tanda ! di depannya jadi pertanda itu ketika tidak cocok dengan passwordnya
            return ApiFormatter::sendResponse(400, 'User not found', 'Silahkan cek kembali email dan password anda!');
        }

        $respondWithToken = [
            'acces_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL()* 60 * 24 // untuk menentukan waktu penyimpanannya
        ];

        return ApiFormatter::sendResponse(200, 'Logged-in', $respondWithToken);
    }

     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()// fungsinya untuk mengambil profil akun yang login
    {
        return ApiFormatter::sendResponse(200, 'succes', auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return ApiFormatter::sendResponse(200, 'succes', 'Berhasil logout');
    }
}
