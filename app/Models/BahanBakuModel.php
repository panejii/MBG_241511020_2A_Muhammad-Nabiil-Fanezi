<?php
namespace App\Models;

use CodeIgniter\Model;

class BahanBakuModel extends Model
{
    protected $table = 'bahan_baku';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama', 'kategori', 'jumlah', 'satuan',
        'tanggal_masuk', 'tanggal_kadaluarsa',
        'status', 'created_at'
    ];

    public function getBahanTersedia()
    {
        return $this->select('*')
                    ->where('jumlah >', 0)
                    ->where('status !=', 'kadaluarsa')
                    ->findAll();
    }
}

?>
