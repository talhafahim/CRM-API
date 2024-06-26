<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class TicketComment extends ResourceController {
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
        $created_by = $this->request->getGet('created_by');
        $created_at = $this->request->getGet('created_at');
        $ticket_id = $this->request->getGet('ticket_id');
        $description = $this->request->getGet('description');
        $deleted = $this->request->getGet('deleted');
        //
        $builder = $this->db->table('crm_ticket_comments');    
        $builder->orderBy('id');
        if(!empty($id)){
            $builder->where('id',$id);
        } if(!empty($created_at)){
            $builder->where('DATE(created_at)',$created_at);
        } if(!empty($deleted)){
            $builder->where('deleted',$deleted); 
        } if(!empty($created_by)){
            $builder->where('created_by',$created_by); 
        } if(!empty($ticket_id)){
            $builder->where('ticket_id',$ticket_id); 
        } if(!empty($description)){
            $builder->where('description',$description); 
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

