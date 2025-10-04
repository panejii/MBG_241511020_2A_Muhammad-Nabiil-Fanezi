<?php namespace App\Models;

use CodeIgniter\Model;

class PermintaanDetailModel extends Model
{
    protected $table      = 'permintaan_detail';
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';

    protected $allowedFields = [
        'permintaan_id', 
        'bahan_id',
        'jumlah_diminta',
        'jumlah_diberi', 
    ];
    
    protected $useTimestamps = false; 

    public function getDetailWithBahanNama($permintaanId)
    {
        return $this->select('permintaan_detail.*, bahan_baku.nama AS nama_bahan, bahan_baku.satuan')
                    ->join('bahan_baku', 'bahan_baku.id = permintaan_detail.bahan_id')
                    ->where('permintaan_detail.permintaan_id', $permintaanId)
                    ->findAll();
    }
}