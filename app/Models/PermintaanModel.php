<?php namespace App\Models;

use CodeIgniter\Model;

class PermintaanModel extends Model
{
    protected $table      = 'permintaan';
    protected $primaryKey = 'id';
    
    protected $returnType = 'array'; 
    
    protected $allowedFields = [
        'pemohon_id', 'tgl_masak', 'menu_makan', 
        'jumlah_porsi', 'status'
    ];
    
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; 

    public function getPermintaanForGudang($status = null, $id = null)
{
    $builder = $this->select('permintaan.*, user.name AS pemohon_nama')
                     ->join('user', 'user.id = permintaan.pemohon_id');
    
    if ($status !== null) {
        $builder->where('permintaan.status', $status);
    }

    if ($id !== null) {
        $builder->where('permintaan.id', $id);
        return $builder->first(); 
    }
    
    return $builder->orderBy('permintaan.created_at', 'DESC')->findAll();
}

}