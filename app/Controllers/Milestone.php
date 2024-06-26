<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class Milestone extends ResourceController {
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
        $description = $this->request->getGet('description');
        $due_date = $this->request->getGet('due_date');
        $project_id = $this->request->getGet('project_id');
        $deleted = $this->request->getGet('deleted');
        //
        $builder = $this->db->table('crm_milestones');    
        $builder->orderBy('id');
        if(!empty($id)){
            $builder->where('id',$id);
        } if(!empty($title)){
            $builder->where('title',$title);
        } if(!empty($due_date)){
            $builder->where('DATE(due_date)',$due_date);
        } if(!empty($description)){
            $builder->where('description',$description);
        } if(!empty($deleted)){
            $builder->where('deleted',$deleted); 
        } if(!empty($project_id)){
            $builder->where('project_id',$project_id); 
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

    public function create()
    {
        $this->db = \Config\Database::connect();
        $modelAuth = new Model_Auth();

        $token = $this->request->getVar('token');
        $description = $this->request->getVar('description');
        $title = $this->request->getVar('title');
        $due_date = $this->request->getVar('due_date');
        $project_id = $this->request->getVar('project_id');
        //
        if(empty($project_id)){
            return $this->fail('project_id is required', 400);
        }if(empty($title)){
            return $this->fail('title is required', 400);
        }if(empty($description)){
            return $this->fail('description is required', 400);
        }if(empty($due_date)){
            return $this->fail('due_date is required', 400);
        }
        
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        $now = date('Y-m-d H:i:s');
        $data = array(
            "title" => $title,
            "description" => $description,
            "project_id" => $project_id ? $project_id : 0,
            "due_date" => $due_date,
        );
        $this->db->table('crm_milestones')->insert($data);
        $ticket_id = $this->db->insertID();
        //
        $response = ['status'   => 200, 'error'    => null, 'messages' => ['success' => 'created successfully'] ];
        //
        return $this->respond($response, 200);
    }
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////


    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    public function delete($id = null)
    {
        $this->db = \Config\Database::connect();
        $modelAuth = new Model_Auth();
        //
        $token = $this->request->getVar('token');
        //
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        $query = $this->db->table('crm_milestones');
        $query->where('id',$id);
        $result = $query->get()->getRow();
        //
        if($result){
            //
            $query = $this->db->table('crm_milestones');
            $query->where('id',$id);
            $query->update(['deleted' => 1]);
            //
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }

    }
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////

}

