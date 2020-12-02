<?php namespace App\Models;

use CodeIgniter\Model;

class UserExam extends Model
{
    protected $table = 'yexam_user_exam';
    protected $primaryKey   = 'id';
    protected $returnType     = 'array';    

    public function get_exams_with_id($id) {
        return $this->where('id_exam', $id)->findAll();
    }

    public function get_exams_for_month($date) {
        $fD = new \DateTime($date);
        $fD->modify('first day of this month')->setTime(0, 0, 0);
        $tD = new \DateTime($date);
        $tD->modify('last day of this month')->setTime(23, 59, 59);

        $this->select();
        $this->where('last_action >=', $fD->format('Y-m-d'));
        $this->where('last_action <=', $tD->format('Y-m-d'));
        
        return $this->findAll();
    }
}