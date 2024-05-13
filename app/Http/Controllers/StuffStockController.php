<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StuffStockController extends Controller
{
    public function __construct() //construct bakal ngejalanin meskipun tidak dipanggil
    //fungsi nya utnuk mengindinisikan middlewernya
    {
        // middlewer : membatasi, nama nama function yang hanya bisa di akses setelah login
        $this->middleware('auth:api', ['except' => ['login', 'logout']]);//except : fungsi mn yg blh diakses sebelum suatu tindakan (login)
    }

}
