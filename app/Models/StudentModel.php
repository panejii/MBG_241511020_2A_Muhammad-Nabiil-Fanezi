<?php

namespace App\Models;
use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table      = 'students';
    protected $primaryKey = 'student_id'; // sesuaikan dengan nama PK di tabel students
    protected $allowedFields = ['user_id', 'entry_year']; // sesuaikan dengan kolom di tabel students
}