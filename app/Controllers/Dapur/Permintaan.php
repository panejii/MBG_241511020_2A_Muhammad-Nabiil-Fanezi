<?php namespace App\Controllers\Dapur;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\PermintaanDetailModel;
use App\Models\BahanBakuModel;

class Permintaan extends BaseController
{
    protected $permintaanModel;
    protected $detailModel;
    protected $bahanModel;

    public function __construct()
    {
        $this->permintaanModel = new PermintaanModel();
        $this->detailModel = new PermintaanDetailModel();
        $this->bahanModel = new BahanBakuModel();
    }

    public function index()
    {
        return $this->create();
    }

    public function create()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'dapur') {
            return redirect()->to('/login');
        }

        $data['bahan_tersedia'] = $this->bahanModel->getBahanTersedia();
        
        return view('dapur/form_permintaan', $data);
    }

    public function send()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'dapur') {
            return redirect()->to('/login');
        }

        $pemohonId = session()->get('id') ?? session()->get('user_id');

        $dataPermintaan = [
            'pemohon_id'     => $pemohonId,
            'tgl_masak'      => $this->request->getPost('tgl_masak'),
            'menu_makan'     => $this->request->getPost('menu_makan'),
            'jumlah_porsi'   => $this->request->getPost('jumlah_porsi'),
            'status'         => 'menunggu', 
        ];
        
        $bahanIds = $this->request->getPost('bahan_id');
        $jumlahDiminta = $this->request->getPost('jumlah_diminta');

        if (empty($bahanIds)) {
            session()->setFlashdata('error', 'Gagal mengajukan permintaan. Minimal harus memilih satu item bahan baku.');
            return redirect()->back()->withInput();
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            if (!$this->permintaanModel->insert($dataPermintaan)) {
                throw new \Exception('Gagal menyimpan header permintaan. ' . print_r($this->permintaanModel->errors(), true));
            }

            $permintaanId = $this->permintaanModel->getInsertID();

            foreach ($bahanIds as $index => $bahanId) {
                $jmlDiminta = $jumlahDiminta[$index] ?? 0;
                
                if (empty($bahanId) || $jmlDiminta <= 0) {
                     throw new \Exception("Data bahan baku atau jumlah permintaan pada baris ke-" . ($index + 1) . " tidak valid.");
                }
                
                $dataDetail = [
                    'permintaan_id' => $permintaanId,
                    'bahan_id'      => $bahanId,
                    'jumlah_diminta'=> $jmlDiminta
                ];
                
                if (!$this->detailModel->insert($dataDetail)) {
                     throw new \Exception("Gagal menyimpan detail bahan baku untuk ID: " . $bahanId);
                }
            }

            $db->transCommit();
            session()->setFlashdata('success', 'Permintaan bahan baku berhasil diajukan dan sedang menunggu persetujuan Gudang.');

        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal mengajukan permintaan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
        
        return redirect()->to('/dapur/permintaan'); 
    }
}