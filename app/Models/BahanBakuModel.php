<?php namespace App\Models;

use CodeIgniter\Model;

class BahanBakuModel extends Model
{
    protected $table = 'bahan_baku';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nama', 'kategori', 'jumlah', 'satuan',
        'tanggal_masuk', 'tanggal_kadaluarsa',
        'status', 'created_at'
    ];

    public function getBahanTersedia()
    {
        // Mengubah pengecekan tanggal dari PHP ($today) menjadi fungsi native MySQL (CURDATE())
        return $this->select('*')
                    ->where('jumlah >', 0)
                    // Menggunakan metode where() dengan string kustom untuk CURDATE()
                    ->where('tanggal_kadaluarsa >=', 'CURDATE()', false)
                    // Syarat 3: Pengecekan status yang tersimpan (jika diperlukan)
                    ->where('status !=', 'KADALUARSA')
                    ->findAll();
    }
}
