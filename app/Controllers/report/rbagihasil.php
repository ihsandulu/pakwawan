<?php

namespace App\Controllers\report;


use App\Controllers\baseController;

class rbagihasil extends baseController
{

    protected $sesi_user;
    public function __construct()
    {
        $sesi_user = new \App\Models\global_m();
        $sesi_user->ceksesi();
    }


    public function index()
    {
        $data = new \App\Models\report\rbagihasil_m();
        $data = $data->data();
        $data["title"]="Laporan Bagi Hasil";
        return view('report/rbagihasil_v', $data);
    }
}
