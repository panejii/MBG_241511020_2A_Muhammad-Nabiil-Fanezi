<?php
namespace App\Controllers\Dapur;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\BahanBakuModel;

class Permintaan extends BaseController
{
    public function index()
    {
        // 1. Pengecekan Akses (Sangat Penting)
        if (!session()->get('logged_in') || session()->get('role') !== 'dapur') {
            return redirect()->to('/login');
        }

        // 2. Inisialisasi Model Permintaan (PERBAIKAN di sini)
        $permintaanModel = new PermintaanModel();
        
        // 3. Ambil data permintaan berdasarkan ID pemohon
        $data['permintaan'] = $permintaanModel
                                    ->where('pemohon_id', session()->get('user_id'))
                                    ->orderBy('created_at', 'DESC') // Urutkan dari yang terbaru
                                    ->findAll();

        // 4. Muat View
        // Pastikan view ini menampilkan riwayat permintaan.
        return view('dapur/daftar_bahan_view', $data);
    }

    public function daftarBahan()
    {
        $bahanModel = new BahanBakuModel();
        
        $data['bahan_baku'] = $bahanModel->getBahanTersedia();
        
        return view('dapur/daftar_bahan_view', $data);
    }
}
?>
