<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BahanBakuModel;

class BahanBaku extends BaseController
{
    public function index()
    {
        $model = new BahanBakuModel();
        $data['bahan_baku'] = $model->findAll();

        return view('admin/bahan_baku', $data);
    }

  public function add()
    {
        return view('admin/add_bahan_baku');
        
    }

    // Tambahkan metode edit, delete, add sesuai kebutuhan nanti
}

?>