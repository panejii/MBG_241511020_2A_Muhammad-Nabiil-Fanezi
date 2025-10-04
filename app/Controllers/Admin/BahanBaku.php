<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BahanBakuModel;

class BahanBaku extends BaseController
{
    protected $bahanModel; 

    public function __construct()
    {
        $this->bahanModel = new BahanBakuModel();
    }

    public function index()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            return redirect()->to('/login');
        }

        $data['bahan_baku'] = $this->bahanModel->orderBy('tanggal_kadaluarsa', 'ASC')->findAll();
        
        return view('admin/bahan_baku', $data); 
    }
    
    public function add()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            return redirect()->to('/login');
        }
        
        return view('admin/add_daftar_bahan');
    }

    public function store()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            return redirect()->to('/login');
        }

        $data = [
            'nama'               => $this->request->getPost('nama'),
            'kategori'           => $this->request->getPost('kategori'),
            'jumlah'             => $this->request->getPost('jumlah'),
            'satuan'             => $this->request->getPost('satuan'),
            'tanggal_masuk'      => $this->request->getPost('tanggal_masuk'),
            'tanggal_kadaluarsa' => $this->request->getPost('tanggal_kadaluarsa'),
            'status'             => 'tersedia', 
        ];

        if ($this->bahanModel->save($data)) {
            
            session()->setFlashdata('success', 'Bahan baku baru berhasil ditambahkan.');
            return redirect()->to('/admin/bahan_baku'); 
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan bahan baku. Cek kembali input Anda.');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            return redirect()->to('/login');
        }

        $bahan = $this->bahanModel->find($id);

        if (!$bahan) {
            session()->setFlashdata('error', 'Bahan baku tidak ditemukan.');
            return redirect()->to('/admin/bahan_baku');
        }

        $data['bahan'] = $bahan;
        return view('admin/edit_bahan_baku', $data);
    }

    public function update($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            return redirect()->to('/login');
        }

        $inputJumlah = $this->request->getPost('jumlah');

            if (!is_numeric($inputJumlah) || (int)$inputJumlah <= 0) {
                session()->setFlashdata('error', 'Update Gagal! Jumlah stok harus lebih besar dari 0.');
                return redirect()->back()->withInput();
            }

        $data = [
            'nama'               => $this->request->getPost('nama'),
            'kategori'           => $this->request->getPost('kategori'),
            'jumlah'             => $inputJumlah,
            'satuan'             => $this->request->getPost('satuan'),
            'tanggal_masuk'      => $this->request->getPost('tanggal_masuk'),
            'tanggal_kadaluarsa' => $this->request->getPost('tanggal_kadaluarsa'),
            'status'             => $this->request->getPost('status'), 
        ];
        
        if ($this->bahanModel->update($id, $data)) {
            session()->setFlashdata('success', 'Bahan baku berhasil diperbarui.');
            return redirect()->to('/admin/bahan_baku');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui bahan baku. Mungkin ada kesalahan input.');
            return redirect()->back()->withInput();
        }
    }

}
