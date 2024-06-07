<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class ProjectComment extends ResourceController {
    use ResponseTrait;
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    public function index() {
        $this->db = \Config\Database::connect();
        $modelAuth = new Model_Auth();
        //
        $token = $this->request->getGet('token');
        //
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        $id = $this->request->getGet('id');
        $created_by = $this->request->getGet('created_by');
        $created_at = $this->request->getGet('created_at');
        $project_id = $this->request->getGet('project_id');
        $task_id = $this->request->getGet('task_id');
        $deleted = $this->request->getGet('deleted');
        //
        $builder = $this->db->table('crm_project_comments');    
        $builder->orderBy('id');
        if(!empty($id)){
            $builder->where('id',$id);
        }  if(!empty($created_by)){
            $builder->where('created_by',$created_by);
        } if(!empty($created_at)){
            $builder->where('DATE(created_at)',$created_at);
        } if(!empty($project_id)){
            $builder->where('project_id',$project_id);
        } if(!empty($task_id)){
            $builder->where('task_id',$task_id);
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

    public function create()
    {
        $this->db = \Config\Database::connect();
        $modelAuth = new Model_Auth();
        //
        $token = $this->request->getVar('token');
        $description = $this->request->getVar('description');
        $project_id = $this->request->getVar('project_id');
        $id = $this->request->getVar('created_by');
        //
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }if(empty($description)){
            return $this->fail('description is required', 400);
        }if(empty($project_id)){
            return $this->fail('project_id is required', 400);
        }if(empty($id)){
            return $this->fail('created_by is required', 400);
        }
        //
        $project_id = $this->request->getVar('project_id');
        $task_id = $this->request->getVar('task_id');
        $file_id = $this->request->getVar('file_id');
        $customer_feedback_id = $this->request->getVar('customer_feedback_id');
        $comment_id = $this->request->getVar('comment_id');
        $description = $this->request->getVar('description');
        //
        $data = array(
            "project_id" => $project_id,
            "file_id" => $file_id ? $file_id : 0,
            "task_id" => $task_id ? $task_id : 0,
            "customer_feedback_id" => $customer_feedback_id ? $customer_feedback_id : 0,
            "comment_id" => $comment_id ? $comment_id : 0,
            "description" => $description
        );
        //
        $data["created_at"] = date('Y-m-d H:i:s');
        $data["created_by"] = $id;
        //
        $this->db->table('crm_project_comments')->insert($data);
        //
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Success'
            ]
        ];
        //
        return $this->respond($response, 200);
    }
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
        $query = $this->db->table('crm_project_comments');
        $query->where('id',$id);
        $result = $query->get()->getRow();
        //
        if($result){
            //
            $query = $this->db->table('crm_project_comments');
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

