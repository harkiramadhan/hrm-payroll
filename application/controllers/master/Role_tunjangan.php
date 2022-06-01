<?php
class Role_tunjangan extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model([
            'M_Company',
        ]);
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Role Tunjangan',
            'company' => $this->M_Company->getDefault(),
            'page' => 'master/role_tunjangan'
        ];
        $this->load->view('templates', $var);
    }
}