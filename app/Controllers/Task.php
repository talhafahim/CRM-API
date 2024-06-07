<?php

namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Model_Auth;

class Task extends ResourceController {
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
        $status = $this->request->getGet('status');
        $start_date = $this->request->getGet('start_date');
        $created_date = $this->request->getGet('created_date');
        $project_id = $this->request->getGet('project_id');
        $deadline = $this->request->getGet('deadline');
        $assigned_to = $this->request->getGet('assigned_to');
        $from = $this->request->getGet('from');
        $to = $this->request->getGet('to');
        //
        $builder = $this->db->table('crm_tasks');    
        $builder->orderBy('id');
        //
        if(!empty($id)){
            $builder->where('id',$id);
        }  if(!empty($status)){
            $builder->where('status',$status);
        } if(!empty($start_date)){
            $builder->where('DATE(start_date)',$start_date);
        } if(!empty($created_date)){
            $builder->where('DATE(created_date)',$created_date);
        } if(!empty($deadline)){
            $builder->where('DATE(deadline)',$deadline);
        } if(!empty($project_id)){
            $builder->where('project_id',$project_id);
        } if(!empty($assigned_to)){
            $builder->where('assigned_to',$assigned_to);
        } if(!empty($from)){
            $builder->where('DATE(created_date) >=',$from);
        } if(!empty($to)){
            $builder->where('DATE(created_date) <=',$to);
        }
        //
        $list_data = $builder->get()->getResult();
        //
        if($list_data){
            //
            foreach($list_data as $value){
                /////// assign to 
                $userDetail = $this->db->table('crm_users')->select('id,first_name,last_name,email,job_title')->where('id',$value->assigned_to)->get()->getRow();
                if($userDetail){
                    $value->assigned_toDetail = $userDetail;
                }else{
                    $value->assigned_toDetail = NULL;
                }
                //////// collaborators
                $taskCollaborator = array();
                if(!empty($value->collaborators)){
                    $collaborators = explode(',',$value->collaborators);
                    
                    foreach($collaborators as $collval){
                    //
                    $collaboratorsDetail = $this->db->table('crm_users')->select('id,first_name,last_name,email,job_title')->where('id',$collval)->get()->getRow();
                    if($collaboratorsDetail){
                        //
                        array_push($taskCollaborator,$collaboratorsDetail);
                    }
                    //
                    }
                    $value->collaboratorsDetail = $taskCollaborator;
                }

            }
            //
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
        $start_date = $this->request->getVar('start_date');
        $assigned_to = $this->request->getVar('assigned_to');
        $collaborators = $this->request->getVar('collaborators');
        $recurring = $this->request->getVar('recurring') ? 1 : 0;
        $repeat_every = $this->request->getVar('repeat_every');
        $repeat_type = $this->request->getVar('repeat_type');
        $no_of_cycles = $this->request->getVar('no_of_cycles');
        $status_id = $this->request->getVar('status_id');
        $priority_id = $this->request->getVar('priority_id');
        $project_id = $this->request->getVar('project_id');
        $title =$this->request->getVar('title');
        $milestone_id = $this->request->getVar('milestone_id');
        $points = $this->request->getVar('points');
        
        //
        if(empty($token) || !$modelAuth->verify_token_key($token)){
            return $this->failUnauthorized('Access denied'); 
        }if(empty($title)){
            return $this->fail('title is required', 400);
        }if(empty($project_id)){
            return $this->fail('project_id is required', 400);
        }

        //
        $data = array(
            "title" => $title,
            "description" => $this->request->getVar('description'),
            "project_id" => $project_id,
            "milestone_id" => $milestone_id ? $milestone_id : '-',
            "points" => $points ? $points : 0,
            "status_id" => $status_id ? $status_id : 1,
            "priority_id" => $priority_id ? $priority_id : 0,
            "labels" => $this->request->getVar('labels'),
            "start_date" => $start_date,
            "deadline" => $this->request->getVar('deadline'),
            "recurring" => $recurring,
            "repeat_every" => $repeat_every ? $repeat_every : 0,
            "repeat_type" => $repeat_type ? $repeat_type : NULL,
            "no_of_cycles" => $no_of_cycles ? $no_of_cycles : 0,
        );
        //
        $data["created_date"] = date('Y-m-d H:i:s');
        if(!empty($assigned_to)){
            $data["assigned_to"] = $assigned_to;    
        }if(!empty($assigned_to)){
            $data["collaborators"] = $collaborators;   
        }
        
        
        //
        $this->db->table('crm_tasks')->insert($data);
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
        $query = $this->db->table('crm_tasks');
        $query->where('id',$id);
        $result = $query->get()->getRow();
        //
        if($result){
            //
            $query = $this->db->table('crm_tasks');
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
