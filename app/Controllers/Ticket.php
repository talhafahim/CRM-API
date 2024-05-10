<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class Ticket extends ResourceController {
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
        $client_id = $this->request->getGet('client_id');
        $project_id = $this->request->getGet('project_id');
        $created_at = $this->request->getGet('created_at');
        $status = $this->request->getGet('status');
        $deleted = $this->request->getGet('deleted');
        //
        $builder = $this->db->table('crm_tickets');    
        $builder->orderBy('id');
        if(!empty($id)){
            $builder->where('id',$id);
        } if(!empty($user_id)){
            $builder->where('client_id',$client_id);
        } if(!empty($created_at)){
            $builder->where('DATE(created_at)',$created_at);
        } if(!empty($status)){
            $builder->where('status',$status);
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
        $client_id = $this->request->getVar('client_id');
        $title = $this->request->getVar('title');
        $ticket_type_id = $this->request->getVar('ticket_type_id');
        $created_by = $this->request->getVar('created_by'); 
        $description = $this->request->getVar('description'); 
        //
        $assigned_to = $this->request->getVar('assigned_to');
        $requested_by = $this->request->getVar('requested_by');
        $labels = $this->request->getVar('labels');
        $project_id = $this->request->getVar('project_id');
        //
        if(empty($client_id)){
            return $this->fail('client_id is required', 400);
        }if(empty($title)){
            return $this->fail('title is required', 400);
        }if(empty($description)){
            return $this->fail('description is required', 400);
        }
        
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        $now = date('Y-m-d H:i:s');
        $data = array(
            "title" => $title,
            "client_id" => $client_id,
            "project_id" => $project_id ? $project_id : 0,
            "ticket_type_id" => $ticket_type_id,
            "created_by" => $created_by,
            "created_at" => $now,
            "last_activity_at" => $now,
            "labels" => $labels,
            "assigned_to" => $assigned_to ? $assigned_to : 0,
            "requested_by" => $requested_by ? $requested_by : 0
        );
        $this->db->table('crm_tickets')->insert($data);
        $ticket_id = $this->db->insertID();
        //
        if($ticket_id){
        $comment_data = array(
            "description" => $description,
            "ticket_id" => $ticket_id,
            "created_by" => $created_by,
            "created_at" => $now
        );
        $this->db->table('crm_ticket_comments')->insert($comment_data);
        }
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
        $query = $this->db->table('crm_tickets');
        $query->where('id',$id);
        $result = $query->get()->getRow();
        //
        if($result){
            //
            $query = $this->db->table('crm_tickets');
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
