<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class Users extends ResourceController {
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
        if(empty($token) || !$modelAuth->verify_token_key($token) ){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        $id = $this->request->getGet('id');
        $email = $this->request->getGet('email');
        $created_at = $this->request->getGet('created_at');
        $status = $this->request->getGet('status');
        $deleted = $this->request->getGet('deleted');
        $role_id = $this->request->getGet('role_id');
        //
        $builder = $this->db->table('crm_users');    
        $builder->orderBy('id');
        //
        if(!empty($id)){
            $builder->where('id',$id);
        }  if(!empty($created_at)){
            $builder->where('DATE(created_at)',$created_at);
        } if(!empty($status)){
            $builder->where('status',$status);
        } if(!empty($deleted)){
            $builder->where('deleted',$deleted); 
        } if(!empty($role_id)){
            $builder->where('role_id',$role_id); 
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
        $first_name = $this->request->getVar('first_name');
        $last_name = $this->request->getVar('last_name');
        $is_admin = $this->request->getVar('is_admin');
        $address = $this->request->getVar('address');
        $phone = $this->request->getVar('phone');
        $gender = $this->request->getVar('gender');
        $job_title = $this->request->getVar('job_title');
        $gender = $this->request->getVar('gender');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar("password");
        $role = $this->request->getVar('role');
        $biometric_img = $this->request->getVar('biometric_img');
        //
        if(empty($token) || !$modelAuth->verify_token_key($token) ){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        if(empty($email)){
            return $this->fail('email is required', 400);
        }if(empty($first_name)){
            return $this->fail('first_name is required', 400);
        }if(empty($last_name)){
            return $this->fail('last_name is required', 400);
        }if(empty($job_title)){
            return $this->fail('job_title is required', 400);
        }if(empty($role)){
            return $this->fail('role is required', 400);
        }if(empty($password)){
            return $this->fail('password is required', 400);
        }
        //
        $emailExist = $this->db->table('crm_users')->where('email',$email)->countAllResults();
        if($emailExist > 0){
            return $this->fail('The email address you have entered is already registered.', 400);
        }
        //
        $user_data = array(
            "email" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "is_admin" => $is_admin,
            "address" => $address,
            "phone" => $phone,
            "gender" => $gender,
            "job_title" => $job_title,
            "user_type" => "staff",
            "created_at" => date('Y-m-d H:i:s')
        );
        if ($password) {
            $user_data["password"] = password_hash($password, PASSWORD_DEFAULT);
        }if($biometric_img){
            $user_data['biometric_img'] = $biometric_img;
        }
        //
        $role_id = $role;
        //
        if ($role === "admin") {
            $user_data["is_admin"] = 1;
            $user_data["role_id"] = 0;
        } else {
            $user_data["is_admin"] = 0;
            $user_data["role_id"] = $role_id;
        }
        //
        $this->db->table('crm_users')->insert($user_data);
        $user_id = $this->db->insertID();
        //
        $response = ['status'   => 200, 'error'    => null, 'messages' => ['success' => 'created successfully'] ];
        //
        return $this->respond($response, 200);
    }
    ////////////////////////////////////////////
    ////////////////////////////////////////////
    ////////////////////////////////////////////

    public function update($user_id = NULL)
    {
        $this->db = \Config\Database::connect();
        $modelAuth = new Model_Auth();

        $token = $this->request->getVar('token');
        $first_name = $this->request->getVar('first_name');
        $last_name = $this->request->getVar('last_name');
        $is_admin = $this->request->getVar('is_admin');
        $address = $this->request->getVar('address');
        $phone = $this->request->getVar('phone');
        $job_title = $this->request->getVar('job_title');
        $gender = $this->request->getVar('gender');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar("password");
        $role = $this->request->getVar('role');
        $biometric_img = $this->request->getVar('biometric_img');
        // 
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        if(empty($user_id)){
            return $this->fail('User ID not found', 400);
        }
        //
        $data = array();
        if(!empty($first_name)){
            $data['first_name'] = $first_name;
        }if(!empty($last_name)){
            $data['last_name'] = $last_name;
        }if(!empty($is_admin)){
            $data['is_admin'] = $is_admin;
        }if(!empty($gender)){
            $data['gender'] = $gender;
        }if(!empty($job_title)){
            $data['job_title'] = $job_title;
        }if(!empty($biometric_img)){
            $data['biometric_img'] = $biometric_img;
        }if(!empty($phone)){
            $data['phone'] = $phone;
        }if(!empty($email)){
            $data['email'] = $email;
        }if(!empty($password)){
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }if(!empty($role)){
            //
         $role_id = $role;
         //
         if($role === "admin"){
            $user_data["is_admin"] = 1;
            $user_data["role_id"] = 0;
        }else{
            $user_data["is_admin"] = 0;
            $user_data["role_id"] = $role_id;
        }
        //
        }if(!empty($address)){
        $data['address'] = $address;
        }
        //
    $this->db->table('crm_users')->where('id',$user_id)->update($data);
        //
    $response = ['status'   => 200, 'error'    => null, 'messages' => ['success' => 'updated successfully'] ];
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
    $query = $this->db->table('crm_users');
    $query->where('id',$id);
    $result = $query->get()->getRow();
        //
    if($result){
            //
        $query = $this->db->table('crm_users');
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

