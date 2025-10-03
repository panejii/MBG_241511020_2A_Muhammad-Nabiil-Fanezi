<?php namespace App\Models;

use CodeIgniter\Model;

class PermintaanModel extends Model
{
    // Nama tabel di database
    protected $table      = 'permintaan';
    
    // Primary Key dari tabel
    protected $primaryKey = 'id';
    
    // Tipe kembalian (array atau object)
    protected $returnType = 'array';
    
    // Field yang diizinkan untuk diisi (digunakan untuk operasi insert/update)
    protected $allowedFields = [
        'pemohon_id', 'tgl_masak', 'menu_makan', 
        'jumlah_porsi', 'status', 'created_at'
    ];
    
    // Tanggal dibuat akan otomatis diurus oleh CI4
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime'; // Sesuaikan jika Anda menggunakan 'date'
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Karena tabel 'permintaan' tidak memiliki 'updated_at'
}
