<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class TaskPriority extends ResourceController {
    use ResponseTrait;
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    public function index() {
        //
        $modelAuth = new Model_Auth();
        //
        $this->db = \Config\Database::connect();
        //
        $token = $this->request->getGet('token');
        //
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        $id = $this->request->getGet('id');
        $title = $this->request->getGet('title');
        $icon = $this->request->getGet('icon');
        $color = $this->request->getGet('color');
        $deleted = $this->request->getGet('deleted');
        //
        $builder = $this->db->table('crm_task_priority');    
        $builder->orderBy('id');
        if(!empty($id)){
            $builder->where('id',$id);
        } if(!empty($title)){
            $builder->where('title',$title);
        } if(!empty($icon)){
            $builder->where('icon',$icon);
        } if(!empty($color)){
            $builder->where('color',$color);
        } if(!empty($deleted)){
            $builder->where('deleted',$deleted); 
        } 
        //
        $list_data = $builder->get()->getResult();
        //
        if($list_data){
            return $this->respond($list_data);
        }else{
            return $this->failNotFound('No Data Found');
        }
    }

   
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////

}

