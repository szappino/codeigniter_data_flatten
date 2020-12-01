<?php namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

use App\Models\UserExam;
use App\Models\Exam;
use App\Models\FlattenedData;

class DataFlatten extends BaseCommand
{
    protected $group       = 'OAM';
    protected $name        = 'data:flatten';
    protected $description = 'Insert flattened data. params: period: the time frame you want to extract the data from in format yyyy-mm-dd';

    public function run(array $params)
    {        
        //Check for wrong parameters number        
        if(count($params) > 1 || count($params) == 0) {
            CLI::write(CLI::color("Error: 1 argument accepted, use spark help $this->name for info", 'red'));
            exit;
        }   

        $now = new \DateTime;               
        $period = new \DateTime($params[0]);
        
        //Check if the selected period is a past date, if not, exit
        if ($period->format('Y-m-d') >= $now->format('Y-m-d')) {
            CLI::write(CLI::color("Error: selected period is not valid", 'red'));
            exit;
        }        

        $user_exams = new UserExam;
        $ids = $user_exams->get_all_exams_ids();
        
        $ex = new Exam;
     
        $exam_name = '';
        $total = $completed = $failed = null;
        $execution_times = [];

        //Perform operation for every exam type
        foreach ($ids as $id) {          
            
            //For each exam from the current type
            foreach ($user_exams->get_exams_with_id($id) as $exam) {

                $exam_date = new \DateTime($exam['last_action']);                
                //Check for the timestamp: if it match perform operation                
                if ($period->format('m') == $exam_date->format('m') && $period->format('Y') == $exam_date->format('Y')) {
                
                    $exam_name = $ex->get_exam_name($id);
                    ($exam['done'] == 1) ? $completed += 1 : $failed += 1;
                    $total += 1;

                    $time = $exam['timing'] - $exam['timing_remain'];
                    array_push($execution_times, $time);
                }
            }

            if ($total != null && $total > 0) {
                $average_execution_time = array_sum($execution_times) / count($execution_times);
                
                //Insert data in the appropriate table 
                $d_f = new FlattenedData;
                $d_f->insert([
                    'period' => $period->format('Y-m-d'),
                    'type' => $exam_name,
                    'tot_simulation_done' => $total,
                    'tot_simulation_finished' => $completed,
                    'tot_simulation_undone' => $failed,
                    'timing_for_do' => $average_execution_time,
                ]);
            
            } else {
                CLI::write(CLI::color("No exams for the specified period.", 'yellow'));
                exit;
            }

            //Reset for next exam type
            $exam_name = '';
            $total = $completed = $failed = 0;
            $execution_times = [];

        }
        
        
    }
}