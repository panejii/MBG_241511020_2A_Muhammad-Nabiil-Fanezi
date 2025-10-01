<?php
namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;

class Permintaan  extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'dapur') {
            return redirect()->to('/login');
        }

        $model = new PermintaanModel();
        $data['permintaan'] = $model->where('pemohon_id', session()->get('user_id'))->findAll();

        return view('student/permintaanDapur', $data);
    }
}
?>
