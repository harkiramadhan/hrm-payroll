<?php
class Pph extends CI_Controller{
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
            'title' => 'Master PPH21',
            'company' => $this->M_Company->getDefault(),
            'page' => 'master/pph'
        ];
        $this->load->view('templates', $var);
    }
}