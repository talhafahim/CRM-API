<?php namespace App\Models;

use CodeIgniter\Model;

class Model_Auth extends Model {
    
    public function __construct(){

        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    //
    function verify_token_key($token){
        //
        if(((strlen($token) % 2) != 0)  || !ctype_xdigit($token)){
            return false;
        }
        //
        $password = hex2bin($token);
        $builder = $this->db->table('crm_users');
        $builder->where('password',$password);
        $count = $builder->countAllResults();
        if($count > 0){
            return true;
        }else{
           return false; 
        }
    }
    //
    function generate_token_key($var){
        if(!empty($var)){
            return bin2hex($var);
        }else{
            return NULL;
        }
    }
}