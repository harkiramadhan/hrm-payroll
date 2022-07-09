<?php
class M_Cutoff extends CI_Model{
    function getActive($companyid=false){
        if($companyid){
            $this->db->where('company_id', $companyid);
        }
        return $this->db->get_where('cutoff', ['is_active' => 't'])->row();
    }
}