<?php
class M_Cutoff extends CI_Model{
    function getActive(){
        return $this->db->get_where('cutoff', ['is_active' => 't'])->row();
    }
}