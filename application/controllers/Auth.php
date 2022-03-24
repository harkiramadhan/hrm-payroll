<?php
class Auth extends CI_Controller{
    function __construct(){
        parent::__construct();
        
        $this->load->model('M_User');
    }
    function index(){
        $this->load->view('login');
    }

    function login(){
        $username = $this->input->post('username', TRUE);
        $password = md5($this->input->post('password', TRUE));

        $cekUser = $this->M_User->getUser($username);
        if($cekUser->num_rows() > 0){
            $user = $cekUser->row();
            if($user->password == $password){
                if($user->status == 't'){
                    $this->session->set_userdata('masuk', TRUE);
                    $this->session->set_userdata('userid', $user->id);
                    $this->session->set_userdata('username', $user->username);

                    redirect('dashboard', "refresh");
                }else{
                    $this->session->set_flashdata('error', "User Not Active");
                    redirect($_SERVER['HTTP_REFERER']);
                }

            }else{
                $this->session->set_flashdata('error', "Password Not Match");
                redirect($_SERVER['HTTP_REFERER']);
            }
        }else{
            $this->session->set_flashdata('error', "User Not Found");
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}