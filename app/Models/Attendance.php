<?php

namespace App\Models;

use CodeIgniter\Model;

class Attendance extends Model
{
    protected $DBGroup = 'default';  
    protected $table      = 'crm_attendance';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['title', 'description'];

}