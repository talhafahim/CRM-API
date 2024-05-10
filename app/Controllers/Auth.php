<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class Auth extends ResourceController {
    use ResponseTrait;
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    public function index() {
        $this->db = \Config\Database::connect();
        $modelAuth = new Model_Auth();
        //
        $email = $this->request->getGet('email');
        $password = $this->request->getGet('password');
        //
        if(empty($email)){
            return $this->fail('email is required', 400);
        }if(empty($password)){
            return $this->fail('password is required', 400);
        }
        //
        $builder = $this->db->table('crm_users');    
        //
        $builder->where('email',$email);
        $builder->where('status','active');
        $builder->where('deleted',0);
        $builder->where('disable_login',0);
        //
        $list_data = $builder->get()->getRow();
        //
        if($list_data){
            //
            if(!password_verify($password,$list_data->password)){
                return $this->failNotFound('Invalid email or password');
            }
            //
            // $list_data->token = 'Hb9XYWkGsY9MKL0z45F3Ay05rp3keyaompBjRwxeCfsCdlW74dDSN5';
            $list_data->token = $modelAuth->generate_token_key($list_data->password);
            //
            return $this->respond($list_data);
        }else{
            return $this->failNotFound('No Data Found');
        }
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////

   

}

