<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class Label extends ResourceController {
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
        $color = $this->request->getGet('color');
        $context = $this->request->getGet('context');
        $user_id = $this->request->getGet('user_id');
        $deleted = $this->request->getGet('deleted');
        //
        $builder = $this->db->table('crm_labels');    
        $builder->orderBy('id');
        if(!empty($id)){
            $builder->where('id',$id);
        } if(!empty($title)){
            $builder->where('title',$title);
        } if(!empty($color)){
            $builder->where('color',$color); 
        } if(!empty($context)){
            $builder->where('context',$context); 
        } if(!empty($user_id)){
            $builder->where('user_id',$user_id); 
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

        $token = $this->request->getVar('token');
        $title = $this->request->getVar('title');
        $color = $this->request->getVar('color');
        $user_id = $this->request->getVar('user_id');
        $context = $this->request->getVar('context');
        //
        if(empty($title)){
            return $this->fail('title is required', 400);
        }if(empty($color)){
            return $this->fail('color is required', 400);
        }
        //
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        $data = array(
            "title" => $title,
            "color" => $color,
            "context" => $context,
            "user_id" => $user_id ? $user_id : 0,
        );
        $this->db->table('crm_labels')->insert($data);
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
        }if(empty($id)){
            return $this->fail('ID is required', 400);
        }
        //
        $query = $this->db->table('crm_labels');
        $query->where('id',$id);
        $result = $query->get()->getRow();
        //
        if($result){
            //
            $query = $this->db->table('crm_labels');
            $query->where('id',$id);
            $query->update(['deleted' => 0]);
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

