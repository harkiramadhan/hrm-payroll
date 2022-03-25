<?php
class Dashboard extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model([
            'M_Company'
        ]);
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Dashboard',
            'company' => $this->M_Company->getDefault(),
        ];
        $this->load->view('templates', $var);
    }
}