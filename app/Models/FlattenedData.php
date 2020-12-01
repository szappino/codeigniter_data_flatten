<?php namespace App\Models;

use CodeIgniter\Model;

class FlattenedData extends Model
{
    protected $table = 'yexam_flattened_data';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'period',
        'type',
        'tot_simulation_done',
        'tot_simulation_finished',
        'tot_simulation_undone',
        'timing_for_do',
    ];
}