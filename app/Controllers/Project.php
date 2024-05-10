<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class Project extends ResourceController {
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
        $client_id = $this->request->getGet('client_id');
        $status = $this->request->getGet('status');
        $start_date = $this->request->getGet('start_date');
        $deadline = $this->request->getGet('deadline');
        $project_label = $this->request->getGet('project_label');
        //
        $builder = $this->db->table('crm_projects');    
        $builder->orderBy('id');
        if(!empty($id)){
            $builder->where('id',$id);
        }  if(!empty($status)){
            $builder->where('status',$status);
        } if(!empty($start_date)){
            $builder->where('DATE(start_date)',$start_date);
        } if(!empty($client_id)){
            $builder->where('client_id',$client_id);
        } if(!empty($deadline)){
            $builder->where('DATE(deadline)',$deadline);
        } if(!empty($project_label)){
            $builder->where('label',$project_label);
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
        $title = $this->request->getVar('title');
        $client_id = $this->request->getVar('client_id');
        $price = $this->request->getVar('price');
        $id = $this->request->getVar('created_by');
        //
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }if(empty($title)){
            return $this->fail('title is required', 400);
        }if(empty($client_id)){
            return $this->fail('client_id is required', 400);
        }if(empty($id)){
            return $this->fail('created_by is required', 400);
        }
        //
        $estimate_id = $this->request->getVar('estimate_id');
        $status = $this->request->getVar('status');
        $order_id = $this->request->getVar('order_id');
        
        //
        $data = array(
            "title" => $title,
            "description" => $this->request->getVar('description'),
            "client_id" => $client_id,
            "start_date" => $this->request->getVar('start_date') ?? NULL,
            "deadline" => $this->request->getVar('deadline') ?? NULL,
            "price" => $price ? $price : 0,
            "labels" => $this->request->getVar('labels'),
            "status" => $status ? $status : "open",
            "estimate_id" => $estimate_id ? $estimate_id : 0,
            "order_id" => $order_id ? $order_id : 0,
        );
        //
        $data["created_date"] = date('Y-m-d H:i:s');
        $data["created_by"] = $id;
        //
        $this->db->table('crm_projects')->insert($data);
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
        $query = $this->db->table('crm_projects');
        $query->where('id',$id);
        $result = $query->get()->getRow();
        //
        if($result){
            //
            $query = $this->db->table('crm_projects');
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

