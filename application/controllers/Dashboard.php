<?php
class Dashboard extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model([
            'M_Company'
        ]);
        $this->companyid = $this->session->userdata('company_id');
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Dashboard',
            'company' => $this->M_Company->getById($this->companyid),
            'page' => 'dashboard'
        ];
        $this->load->view('templates', $var);
    }
}