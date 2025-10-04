<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PermintaanModel; 
use App\Models\PermintaanDetailModel; 
use App\Models\BahanBakuModel; 
use App\Models\UserModel; // WAJIB ADA

class Permintaan extends BaseController
{
    protected $permintaanModel;
    protected $detailModel;
    protected $bahanModel;
    protected $userModel; 

    public function __construct()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Akses ditolak. Hanya Role Gudang yang diizinkan.');
        }

        $this->permintaanModel = new PermintaanModel();
        $this->detailModel = new PermintaanDetailModel(); 
        $this->bahanModel = new BahanBakuModel(); 
        $this->userModel = new UserModel(); 
    }

    public function index()
    {
        $data['list_permintaan'] = $this->permintaanModel->getPermintaanForGudang('menunggu'); 

        return view('admin/list_permintaan_gudang', $data);
    }
    
    public function show($id)
    {
        $permintaan = $this->permintaanModel->find($id);

        if (!$permintaan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Permintaan tidak ditemukan.');
        }

        $pemohon = $this->userModel->find($permintaan->pemohon_id);
        $pemohonNama = $pemohon ? $pemohon->name : 'Pemohon Tidak Dikenal';

        $detailPermintaan = $this->detailModel
                                 ->select('permintaan_detail.*, bahan_baku.nama, bahan_baku.satuan, bahan_baku.jumlah AS stok_saat_ini')
                                 ->join('bahan_baku', 'bahan_baku.id = permintaan_detail.bahan_id')
                                 ->where('permintaan_id', $id)
                                 ->findAll(); 
        
        $data = [
            'title'      => 'Detail Permintaan #' . $id,
            'permintaan' => $permintaan,
            'detail'     => $detailPermintaan, 
            'pemohon_nama' => $pemohonNama, 
        ];
        
        $data['permintaan']->pemohon_nama = $pemohonNama;


        return view('admin/list_permintaan_gudang', $data);
    }

    public function detail($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            return redirect()->to('/login');
        }

        $data['permintaan'] = $this->permintaanModel->getPermintaanForGudang(null, $id); 
        
        if (empty($data['permintaan'])) {
            session()->setFlashdata('error', 'Permintaan tidak ditemukan.');
            return redirect()->to('/admin/showPermintaan');
        }

        $data['detail_bahan'] = $this->detailModel->getDetailWithBahanNama($id); 
        
        return view('admin/detail_permintaan_gudang', $data); 
    }
    
    public function process($id)
    {
        session()->setFlashdata('error', 'Fitur proses sedang dikembangkan. Data berhasil tampil!');
        return redirect()->back();
    }
}
