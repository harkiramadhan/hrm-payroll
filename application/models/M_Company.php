<?php
class M_Company extends CI_Model{
    function getDefault(){
        return $this->db->select('*')
                        ->from('company')
                        ->where([
                            'is_default' => 't'
                        ])->get()->row();
    }
}