<?php
class admin extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('funds_account_admin');
		$this->load->helper("url");
		$this->load->library("session");
	}
	public function index() {
			if ($this->input->post()) {
			    $username   = $this->input->post("login_username");
	            $password   = $this->input->post("login_password");
	            $password   = md5($password);
	            if ($this->funds_account_admin->check_admin($username,$password)) {
	            	$this->session->set_userdata('username', $username);
	            	$this->session->set_userdata('password', $password);
	            	header("Location: " . base_url('index.php/admin/main_page'));
	            	return;
	            }
	        }

	        if ($this->check_login_state()) {
	        	header("Location: " . base_url('index.php/admin/main_page'));
            	return;
	        }
			$this->load->view('index.php');
			return;
	}

	public function main_page() {
		$register_list 	= $this->funds_account_admin->get_register_list();
		$lost_list 		= $this->funds_account_admin->get_lost_list();
		$cancel_list 	= $this->funds_account_admin->get_cancel_list();
		$data = array(
			'realName' 		=> $this->session->userdata('username'),	
			'registerNum' 	=> count($register_list),
			'lostNum'		=> count($lost_list),
			'cancelNum'		=> count($cancel_list),
			);
		$this->load->view("main_head",array("active"=>""));
		$this->load->view('main_page.php', array('user' => $data));
	}

	public function confirm_register() {
		$register_list 	= $this->funds_account_admin->get_register_list();
		if ($this->input->post()) {
			$confirm = $this->input->post("confirm");
			$id 	 = $this->input->post("id");
			// $delete  = $this->input->post("delete");
			if ($confirm) {
				$this->funds_account_admin->handle_register($id, true);
				header("Location: " . base_url('index.php/admin/confirm_register'));
				return;
			}
			// if ($delete) {
			// 	// echo $delete;
			// 	$this->funds_account_admin->handle_register($id, false);
			// }
		}
		$this->load->view("main_head",array("active"=>"register"));
		$this->load->view("confirm_register",array('users'=>$register_list));
	}

	public function confirm_lost() {
		$lost_list  = $this->funds_account_admin->get_lost_list();
		// echo var_dump($_POST);
		if ($this->input->post()) {
			$confirm = $this->input->post("confirm");
			$id 	 = $this->input->post("id");						
			if ($confirm) {
				echo $id;
				$this->funds_account_admin->handle_lost_application($id,true, 'xxx');
				//header("Location: " . base_url('index.php/admin/confirm_lost'));
				return;	
			}
		}
		$this->load->view('main_head', array('active'=>'lost'));
		$this->load->view("confirm_lost",array('users'=>$lost_list));
	}

	public function confirm_cancel() {
		$cancel_list 	= $this->funds_account_admin->get_cancel_list();
		if ($this->input->post()) {
			$confirm = $this->input->post("confirm");
			$id 	 = $this->input->post("id");						
			$this->funds_account_admin->handle_cancel_application($id,true, 'xxx');
			header("Location: " . base_url('index.php/admin/confirm_cancel'));
			return;
		}

		$this->load->view('main_head', array('active'=>'cancel'));
		$this->load->view("confirm_cancel",array('users'=>$cancel_list));
	}
	private function check_login_state($levelRequirement = 1)
    {
        $this->load->library("session");
        $username      = $this->session->userdata('username');
        $password      = $this->session->userdata('password');
        if (empty($username) || empty($password))
            return false;
        if ($this->funds_account_admin->check_admin($username, $password) !== false)
        {
  			return true;
        }
        return false;
    }
}
?>