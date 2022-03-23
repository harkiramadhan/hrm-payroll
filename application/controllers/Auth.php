<?php
class Auth extends CI_Controller{
    function index(){
        $this->load->view('login');
    }
}