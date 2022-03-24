<?php
class M_User extends CI_Model{
    function getUser($username){
        return $this->db->select('*')
                        ->from('user')
                        ->where([
                            'username' => $username,
                        ])->limit(1)->get();
    }
}