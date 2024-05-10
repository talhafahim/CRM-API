<?php

namespace App\Models;

class Model_Attendance extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'attendance';
        parent::__construct($this->table);
    }

    function current_clock_in_record($user_id) {
        $attendnace_table = $this->db->prefixTable('attendance');
        $sql = "SELECT $attendnace_table.*
        FROM $attendnace_table
        WHERE $attendnace_table.deleted=0 AND $attendnace_table.user_id=$user_id AND $attendnace_table.status='incomplete'";
        $result = $this->db->query($sql);
        if ($result->resultID->num_rows) {
            return $result->getRow();
        } else {
            return false;
        }
    }

    function log_time($user_id, $note = "") {
        $user_id = $user_id ? $this->db->escapeString($user_id) : $user_id;

        $current_clock_record = $this->current_clock_in_record($user_id);

        $now = get_current_utc_time();

        if ($current_clock_record && $current_clock_record->id) {
            $data = array(
                "out_time" => $now,
                "status" => "pending",
                "note" => $note
            );
            return $this->ci_save($data, $current_clock_record->id);
        } else {
            $data = array(
                "in_time" => $now,
                "status" => "incomplete",
                "user_id" => $user_id
            );
            return $this->ci_save($data);
        }
    }
    //
    function get_attendence_detail($id=null,$user_id=null,$in_time=null,$out_time=null,$status=null,$break_start=null,$break_end=null){
        //
        $builder = $this->db->table('crm_attendance');
        $builder->where('deleted' , 0);     
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
        } 
        return $builder;
    }
    //
    
}
