<?php namespace App\Models;

use CodeIgniter\Model;

class UserExam extends Model
{
    protected $table = 'yexam_user_exam';
    protected $primaryKey   = 'id';
    protected $returnType     = 'array';

    public function get_all_exams_ids() {
        $this->distinct();
        $result = $this->findColumn('id_exam');
        return $result;
    }

    public function get_exams_with_id($id) {
        return $this->where('id_exam', $id)->findAll();
    }
}