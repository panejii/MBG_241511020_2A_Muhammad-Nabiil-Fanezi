<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BahanBakuModel;

class BahanBaku extends BaseController
{
    // PROPERTI: Definisikan sebagai $bahanModel (b kecil)
    protected $bahanModel; 

    public function __construct()
    {
        // INISIALISASI: Lakukan inisialisasi pada properti yang benar
        $this->bahanModel = new BahanBakuModel();
        // helper(['form']); // Opsional jika Anda menggunakan form_open()
    }

    /**
     * Menampilkan daftar semua bahan baku (untuk role Gudang). (Tugas 1.b)
     */
    public function index()
    {
        // 1. Pengecekan Akses
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            return redirect()->to('/login');
        }

        // 2. Ambil semua data bahan baku dari Model
        $data['bahan_baku'] = $this->bahanModel->orderBy('tanggal_kadaluarsa', 'ASC')->findAll();
        
        // 3. Muat View
        return view('admin/bahan_baku', $data); 
    }
    
    /**
     * Menampilkan form untuk tambah bahan baku baru.
     */
    public function add()
    {
        // Pengecekan Akses
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            return redirect()->to('/login');
        }
        
        return view('admin/add_daftar_bahan');
    }

    /**
     * Menyimpan data bahan baku yang dikirim dari form.
     */
    public function store()
    {
        // 1. Pengecekan Akses
        if (!session()->get('logged_in') || session()->get('role') !== 'gudang') {
            return redirect()->to('/login');
        }

        // 2. Mendapatkan input dari form
        $data = [
            'nama'               => $this->request->getPost('nama'),
            'kategori'           => $this->request->getPost('kategori'),
            'jumlah'             => $this->request->getPost('jumlah'),
            'satuan'             => $this->request->getPost('satuan'),
            'tanggal_masuk'      => $this->request->getPost('tanggal_masuk'),
            'tanggal_kadaluarsa' => $this->request->getPost('tanggal_kadaluarsa'),
            'status'             => 'tersedia', 
        ];

        // 3. Simpan ke database menggunakan properti yang BENAR: $this->bahanModel
        if ($this->bahanModel->save($data)) {
            // Beri notifikasi sukses
            session()->setFlashdata('success', 'Bahan baku baru berhasil ditambahkan.');
            return redirect()->to('/admin/bahan_baku'); // Redirect ke daftar bahan
        } else {
            // Beri notifikasi gagal jika ada error database 
            session()->setFlashdata('error', 'Gagal menambahkan bahan baku. Cek kembali input Anda.');
            return redirect()->back()->withInput();
        }
    }
}
