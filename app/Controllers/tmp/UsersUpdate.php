<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class UsersUpdate extends ResourceController {
    use ResponseTrait;
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////

    public function create()
    {
        $this->db = \Config\Database::connect();
        $modelAuth = new Model_Auth();

        $id = $this->request->getVar('id');
        $token = $this->request->getVar('token');
        $biometric_img = $this->request->getVar('biometric_img');
        $phone = $this->request->getVar('phone');
        $address = $this->request->getVar('address');
        // 
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        if(empty($id)){
            return $this->fail('ID is required', 400);
        }
        //
        $data = array();
        if(!empty($biometric_img)){
            $data['biometric_img'] = $biometric_img;
        }if(!empty($phone)){
            $data['phone'] = $phone;
        }if(!empty($address)){
            $data['address'] = $address;
        }
        //
        $this->db->table('crm_users')->where('id',$id)->update($data);
        //
        $response = ['status'   => 200, 'error'    => null, 'messages' => ['success' => 'updated successfully'] ];
        //
        return $this->respond($response, 200);
    }
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////

}

