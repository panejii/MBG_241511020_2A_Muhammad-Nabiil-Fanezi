<?php namespace App\Controllers\Dapur;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\PermintaanDetailModel; 


class StatusPermintaan extends BaseController
{
    protected $permintaanModel;

    public function __construct()
    {
        $this->permintaanModel = new PermintaanModel();
        $this->detailModel = new PermintaanDetailModel(); 

    }

    public function index()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'dapur') {
            return redirect()->to('/login');
        }

        $pemohonId = session()->get('id') ?? session()->get('user_id');

        $data['list_permintaan'] = $this->permintaanModel
                                        ->where('pemohon_id', $pemohonId)
                                        ->orderBy('created_at', 'DESC')
                                        ->findAll();
        
        return view('dapur/list_permintaan', $data);
    }

    public function detail($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'dapur') {
            return redirect()->to('/login');
        }

        $pemohonId = session()->get('id') ?? session()->get('user_id');

        $data['permintaan'] = $this->permintaanModel
                                   ->where('id', $id)
                                   ->where('pemohon_id', $pemohonId) 
                                   ->first(); 

        if (empty($data['permintaan'])) {
            session()->setFlashdata('error', 'Permintaan tidak ditemukan atau Anda tidak memiliki akses.');
            return redirect()->to('/dapur/status_permintaan');
        }

        $data['detail_bahan'] = $this->detailModel->getDetailWithBahanNama($id); 

        return view('dapur/detail_permintaan', $data);
    }

}