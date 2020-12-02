<?php namespace App\Models;

use CodeIgniter\Model;

class Exam extends Model
{
    protected $table = 'yexam_exam';
    protected $primaryKey = 'id';

    public function get_all_exams_ids() {
        $this->distinct();
        $result = $this->findColumn('id');
        return $result;
    }

    public function get_exam_name($id) {
        return $this->where('id', $id)->first()['name'];
    }
}