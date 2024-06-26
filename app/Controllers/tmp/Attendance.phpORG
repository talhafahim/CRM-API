<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class Attendance extends ResourceController {
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
        $user_id = $this->request->getGet('user_id');
        $in_time = $this->request->getGet('in_time');
        $out_time = $this->request->getGet('out_time');
        $status = $this->request->getGet('status');
        $deleted = $this->request->getGet('deleted');
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        //
        $builder = $this->db->table('crm_attendance');    
        $builder->orderBy('id');
        if(!empty($id)){
            $builder->where('id',$id);
        } if(!empty($user_id)){
            $builder->where('user_id',$user_id);
        } if(!empty($in_time)){
            $builder->where('DATE(in_time)',$in_time);
        } if(!empty($out_time)){
            $builder->where('DATE(out_time)',$out_time);
        } if(!empty($status)){
            $builder->where('status',$status);
        } if(!empty($break_start)){
            $builder->where('DATE(break_start)',$break_start);
        } if(!empty($break_end)){
            $builder->where('DATE(break_end)',$break_end);
        } if(!empty($deleted)){
            $builder->where('deleted',$deleted); 
        } if(!empty($start_date)){
            $builder->where('DATE(in_time) >=',$start_date);
        } if(!empty($end_date)){
            $builder->where('DATE(in_time) <=',$end_date);
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
        $user_id = $this->request->getVar('user_id');
        
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        if(empty($user_id)){
            return $this->fail('User ID not found', 400);
        }
        //
        $checkIncheck = $this->db->table('crm_attendance')->where('user_id',$user_id)->where('DATE(in_time)',date('Y-m-d'))->countAllResults();
        if($checkIncheck > 0){
            return $this->failResourceExists('You have already checked in today');
        }
        //
        $data = array(
            "in_time" => date('Y-m-d H:i:s'),
            "status" => "incomplete",
            "user_id" => $user_id
        );
        $this->db->table('crm_attendance')->insert($data);
        //
        $response = ['status'   => 200, 'error'    => null, 'messages' => ['success' => 'checked in successfully'] ];
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
        $note = $this->request->getVar('note');
        // 
        
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }
        //
        if(empty($user_id)){
            return $this->fail('User ID not found', 400);
        }
        //
        $checkOutcheck = $this->db->table('crm_attendance')->where('user_id',$user_id)->where('DATE(in_time)',date('Y-m-d'))->where('status','pending')->countAllResults();
        if($checkOutcheck > 0){
            return $this->failResourceExists('You have already checked out today');
        }
        //
        $checkIncheck = $this->db->table('crm_attendance')->where('user_id',$user_id)->where('DATE(in_time)',date('Y-m-d'))->where('status','incomplete')->countAllResults();
        if($checkIncheck <= 0){
            return $this->failResourceExists('kindly check in first');
        }
        //
        $data = array(
            "out_time" => date('Y-m-d H:i:s'),
            "status" => "pending",
            "note" => $note
        );
        $this->db->table('crm_attendance')->where('status','incomplete')->where('user_id',$user_id)->update($data);
        //
        $response = ['status'   => 200, 'error'    => null, 'messages' => ['success' => 'checked out successfully'] ];
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
        $query = $this->db->table('crm_attendance');
        $query->where('id',$id);
        $result = $query->get()->getRow();
        //
        if($result){
            //
            $query = $this->db->table('crm_attendance');
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
    // public function break($user_id)
    // {
    //     $accesstoken = 'Hb9XYWkGsY9MKL0z45F3Ay05rp3keyaompBjRwxeCfsCdlW74dDSN5';

    //     $token = $this->request->getVar('token');

    //     if(empty($token) || $token != $accesstoken){
    //         return $this->failUnauthorized('Access denied'); 
    //     }
    //     //
    //     if(empty($user_id)){
    //         $this->fail('User ID not found', 400);
    //     }
    //     //
    //     $checkDayOut = $this->Attendance_model->get_attendence_detail(NULL,$user_id,date('Y-m-d'))->get()->getRow();
    //     if(!empty($checkDayOut) && $checkDayOut->status == 'pending'){
    //         return $this->failResourceExists('Sorry ! You have already clocked out today');
    //     }if(!empty($checkDayOut) && !empty($checkDayOut->break_end)){
    //         return $this->failResourceExists('Break Time already ended');
    //     }if(empty($checkDayOut)){
    //         return $this->failResourceExists('Please clock in first'); 
    //     }
    //     //
    //     if(($checkDayOut) && ($checkDayOut->status == 'incomplete') && empty($checkDayOut->break_start)  ){
    //         $this->db->table('attendance')->where('user_id',$user_id)->where('DATE(in_time)',date('Y-m-d'))->update(['break_start' => get_current_utc_time()]);

    //     }if(($checkDayOut) && ($checkDayOut->status == 'incomplete') && !empty($checkDayOut->break_start)  ){
    //         $this->db->table('attendance')->where('user_id',$user_id)->where('DATE(in_time)',date('Y-m-d'))->update(['break_end' => get_current_utc_time()]);
    //     }
    //     //
    //     $response = [
    //         'status'   => 200,
    //         'error'    => null,
    //         'messages' => [
    //             'success' => 'Success'
    //         ]
    //     ];
    //     //
    //     return $this->respond($response, 200);
    // }

}

