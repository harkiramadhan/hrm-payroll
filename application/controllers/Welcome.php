<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	function test(){
		$begin = new DateTime( "2022-05-01" );
		$end   = new DateTime( "2022-05-30" );

		$count = 0;
		for($i = $begin; $i <= $end; $i->modify('+1 day')){
			if($i->format('D') == 'Sun' || $i->format('D') == 'Mon'){
				echo $i->format("Y-m-d D")."<br>";
				$count ++;
			}
		}
		echo $count;
	}
	function prof(){
		$this->output->enable_profiler(TRUE);
		
	}

	function sss(){
		$test = $this->db->select('nik')->order_by('CAST(nik AS UNSIGNED)', "DESC")->get_where('pegawai', ['company_id' => 2])->row()->nik;
		echo $test;

		$this->output->enable_profiler(TRUE);
		
	}
}
