<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\StudentModel;

class Courses extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        $courses = $courseModel->findAll(); 

        $student_id = session()->get('student_id');
        $db = \Config\Database::connect();
        $enrollments = $db->table('takes')->where('student_id', $student_id)->get()->getResultArray();

        return view('student/all_courses', [
            'courses' => $courses,
            'enrollments' => $enrollments
        ]);
    }

    public function enrolled()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $student_id = session()->get('student_id');
        $db = \Config\Database::connect();

        $builder = $db->table('takes');
        $builder->select('courses.course_id, courses.course_name, courses.credits, takes.enroll_date');
        $builder->join('courses', 'courses.course_id = takes.course_id');
        $builder->where('takes.student_id', $student_id);
        $mycourses = $builder->get()->getResultArray();

        return view('student/my_courses', [
            'mycourses' => $mycourses
        ]);
    }
    
    public function unenroll($course_id)
    {
        try {
            // Cek apakah user sudah login
            if (!session()->get('logged_in')) {
                // Berikan respons JSON, bukan redirect
                return $this->response->setJSON(['success' => false, 'message' => 'User not logged in.']);
            }

            $student_id = session()->get('student_id');
            $db = \Config\Database::connect();
            $deleted = $db->table('takes')
                        ->where('student_id', $student_id)
                        ->where('course_id', $course_id)
                        ->delete();

            if ($deleted) {
                // Kirim respons JSON jika berhasil
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Berhasil membatalkan Course!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal membatalkan Course.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan di server: ' . $e->getMessage()
            ]);
        }
    }

    public function enrollMultiple()
    {
        try {
            $data = $this->request->getJSON(true);
            
            if (!$data || !isset($data['course_ids'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid JSON input or missing course IDs.'
                ]);
            }

            $courseIds = $data['course_ids'];
            $studentId = session()->get('student_id');

            if (!$studentId) {
                $userId = session()->get('user_id');
                $studentModel = new StudentModel();
                $student = $studentModel->where('user_id', $userId)->first();
                $studentId = $student['student_id'];
                session()->set('student_id', $studentId);
            }

            $db = \Config\Database::connect();
            $takesTable = $db->table('takes');
            $successCount = 0;
            $failedCount = 0;

            foreach ($courseIds as $courseId) {
                $isEnrolled = $takesTable
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->get()
                    ->getRow();

                if (!$isEnrolled) {
                    $takesTable->insert([
                        'student_id'  => $studentId,
                        'course_id'   => $courseId,
                        'enroll_date' => date('Y-m-d')
                    ]);
                    $successCount++;
                } else {
                    $failedCount++;
                }
            }

            $message = "Berhasil mendaftarkan $successCount mata kuliah.";
            if ($failedCount > 0) {
                $message .= " ($failedCount mata kuliah sudah terdaftar).";
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan di server: ' . $e->getMessage()
            ]);
        }
    }
}