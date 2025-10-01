<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\StudentModel;

class Students extends BaseController
{
    public function index()
    {
    if (!session()->get('logged_in')) {
        return redirect()->to('/login');
    }   


        $db = \Config\Database::connect();
        $builder = $db->table('students');
        $builder->select('students.student_id, students.entry_year, users.username, users.full_name');
        $builder->join('users', 'users.user_id = students.user_id');
        $students = $builder->get()->getResultArray();

        return view('admin/manage_students', ['students' => $students]);
    }

    public function add()
    {
        return view('admin/add_student');
    }

    public function save()
    {
        $userModel = new UserModel();
        $studentModel = new StudentModel();

    $userData = [
        'username'  => $this->request->getPost('username'),
        'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'role'      => 'student',
        'full_name' => $this->request->getPost('full_name')
    ];
    $user_id = $userModel->insert($userData);

    $studentModel->insert([
        'user_id'    => $user_id,
        'entry_year' => $this->request->getPost('entry_year')
    ]);

    return redirect()->to('/admin/students');
    }

    public function edit($student_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('students');
        $builder->select('students.student_id, students.entry_year, users.user_id, users.username, users.full_name');
        $builder->join('users', 'users.user_id = students.user_id');
        $builder->where('students.student_id', $student_id);
        $student = $builder->get()->getRowArray();

        return view('admin/edit_student', ['student' => $student]);
    }

    public function update($student_id)
    {
        $studentModel = new StudentModel();
        $userModel = new UserModel();

        // Ambil student untuk dapatkan user_id
        $student = $studentModel->find($student_id);

        // Update users
        $userModel->update($student['user_id'], [
            'username'  => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name')
            // Tambahkan password jika ingin bisa diubah
        ]);

        // Update students
        $studentModel->update($student_id, [
            'entry_year' => $this->request->getPost('entry_year')
        ]);

        return redirect()->to('/admin/students');
    }

    public function delete($student_id)
    {
        try {
            $studentModel = new \App\Models\StudentModel();
            $userModel = new \App\Models\UserModel();

            $student = $studentModel->find($student_id);
            
            if ($student) {
                $user_id = $student['user_id'];
                $studentModel->delete($student_id);
                $userModel->delete($user_id);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Mahasiswa berhasil dihapus.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Mahasiswa tidak ditemukan.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus mahasiswa: ' . $e->getMessage()
            ]);
        }
    }

}