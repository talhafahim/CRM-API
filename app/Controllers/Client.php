<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class Client extends ResourceController {
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
        $created_date = $this->request->getGet('created_date');
        //
        $builder = $this->db->table('crm_clients');    
        $builder->orderBy('id');
        //
        if(!empty($id)){
            $builder->where('id',$id);
        }  if(!empty($created_date)){
            $builder->where('DATE(created_date)',$created_date);
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
        //
         $company_name = $this->request->getVar('company_name');
        //
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }if(empty($company_name)){
            return $this->fail('company_name is required', 400);
        }
        //
        $data = array(
            "company_name" => $company_name,
            "address" => $this->request->getVar('address'),
            "city" => $this->request->getVar('city'),
            "state" => $this->request->getVar('state'),
            "zip" => $this->request->getVar('zip'),
            "country" => $this->request->getVar('country'),
            "phone" => $this->request->getVar('phone'),
            "website" => $this->request->getVar('website'),
            "vat_number" => $this->request->getVar('vat_number')
        );
        //
        $data["created_date"] = date('Y-m-d H:i:s');
        //
        $this->db->table('crm_clients')->insert($data);
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
        $query = $this->db->table('crm_clients');
        $query->where('id',$id);
        $result = $query->get()->getRow();
        //
        if($result){
            //
            $query = $this->db->table('crm_clients');
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

