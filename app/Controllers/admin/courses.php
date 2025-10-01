<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class Courses extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
            }
        $courseModel = new \App\Models\CourseModel();
        $courses = $courseModel->findAll();
        return view('admin/manage_courses', ['courses' => $courses]);
    }

    public function add()
    {
        return view('admin/add_course');
    }

    public function save()
    {
        $courseModel = new CourseModel();
        $courseModel->insert([
            'course_name' => $this->request->getPost('course_name'),
            'credits'     => $this->request->getPost('credits')
        ]);
        return redirect()->to('/admin/courses');
    }

   public function edit($course_id)
    {
        $courseModel = new CourseModel();
        $course = $courseModel->find($course_id);
        return view('admin/edit_course', ['course' => $course]);
    }

    public function update($course_id)
    {
        $courseModel = new CourseModel();
        $courseModel->update($course_id, [
            'course_name' => $this->request->getPost('course_name'),
            'credits'     => $this->request->getPost('credits')
        ]);
        return redirect()->to('/admin/courses');
    }

        public function delete($course_id)
    {
        try {
            $courseModel = new \App\Models\CourseModel();
            $db = \Config\Database::connect();
            
            // 1. Cek apakah ada siswa yang meng-enroll course ini
            $isEnrolled = $db->table('takes')
                             ->where('course_id', $course_id)
                             ->countAllResults() > 0;
            
            if ($isEnrolled) {
                // Jika ada, kirim pesan error yang mudah dipahami
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tidak bisa menghapus course selama masih ada siswa yang meng-enroll.'
                ]);
            }
            
            // 2. Jika tidak ada siswa yang meng-enroll, lanjutkan proses penghapusan
            $course = $courseModel->find($course_id);
            
            if ($course) {
                $courseModel->delete($course_id);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Course berhasil dihapus.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Course tidak ditemukan.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus course: ' . $e->getMessage()
            ]);
        }
    }
}