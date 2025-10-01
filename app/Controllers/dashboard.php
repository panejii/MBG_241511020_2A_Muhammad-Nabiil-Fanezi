<?php

namespace App\Controllers;
use App\Models\CourseModel;
use App\Models\StudentModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $courseModel = new CourseModel();
        $db = \Config\Database::connect();

        $courses = $courseModel->findAll();

        // Join students dan users
        $builder = $db->table('students');
        $builder->select('students.student_id, students.entry_year, users.username, users.full_name');
        $builder->join('users', 'users.user_id = students.user_id');
        $students = $builder->get()->getResultArray();

        return view('dashboard', [
            'courses' => $courses,
            'students' => $students
        ]);
    }
}
