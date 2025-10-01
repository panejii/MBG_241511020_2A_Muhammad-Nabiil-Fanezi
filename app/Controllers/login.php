<?php

namespace App\Controllers;
use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function auth()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            $pass = $user['password'];
            if (password_verify($password, $pass)) {
                $ses_data = [
                    'user_id'   => $user['user_id'],
                    'username'  => $user['username'],
                    'full_name' => $user['full_name'],
                    'role'      => $user['role'],
                    'logged_in' => TRUE
                ];
                $user_id = $user['user_id']; // dari tabel users
                $studentModel = new \App\Models\StudentModel();
                $student = $studentModel->where('user_id', $user_id)->first();
                if ($student) {
                    $ses_data['student_id'] = $student['student_id'];
                }
                $session->set($ses_data);
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
