<?php
class admin extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('funds_account_admin');
		$this->load->model('funds_account_log_manager');
		$this->load->model('funds_account');
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

	public function save(){
		$info = '';
		$successful = 1;
		if( $this->input->post() ){
			$successful = 2;
			$id = $this->input->post( "id" );
			$currency = $this->input->post( "currency" );
			$amount = $this->input->post( "amount" );
			if( strlen($id) == 0 ){
				$info = "资金账户号为空。";
			}
			else if( strlen($currency) == 0 ){
				$info = "币种为空。";
			}
			else if( strlen($amount) == 0 ){
				$info = "金额为空。";
			}
			else if( !is_numeric($amount) ){
				$info = "金额必须为数字。";
			}
			else{
				$result = $this->funds_account->save( $id, $currency, $amount );
				if( $result === true ){
					$info = "存款成功。";
					$successful = 3;
				}
				else{
					$info = $result;
				}
			}
		}
		$vars = array();
		$vars['info'] = $info;
		if( $successful == 2 ){
			$vars['old_id'] = $this->input->post( "id" );
			$vars['old_currency'] = $this->input->post( "currency" );
			$vars['old_amount'] = $this->input->post( "amount" );
		}
		else{
			$vars['old_id'] = '';
			$vars['old_currency'] = '';
			$vars['old_amount'] = '';
		}
		$this->load->view("main_head",array("active"=>"save"));
		$this->load->view("save", $vars );
	}

	public function withdraw(){
		$info = '';
		$successful = 1;
		if( $this->input->post() ){
			$successful = 2;
			$id = $this->input->post( "id" );
			$currency = $this->input->post( "currency" );
			$amount = $this->input->post( "amount" );
			$withdraw_password = $this->input->post( "withdraw_password" );
			if( strlen($id) == 0 ){
				$info = "资金账户号为空。";
			}
			else if( strlen($currency) == 0 ){
				$info = "币种为空。";
			}
			else if( strlen($amount) == 0 ){
				$info = "金额为空。";
			}
			else if( !is_numeric($amount) ){
				$info = "金额必须为数字。";
			}
			else if( strlen( $this->input->post( "withdraw_password" ) ) == 0 ){
				$info = "取款密码为空。";
			}
			else{
				$result = $this->funds_account->withdraw( $id, $currency, $amount, $withdraw_password );
				if( $result === true ){
					$info = "取款成功。";
					$successful = 3;
				}
				else{
					$info = $result;
				}
			}
		}
		$vars = array();
		$vars['info'] = $info;
		if( $successful == 2 ){
			$vars['old_id'] = $this->input->post( "id" );
			$vars['old_currency'] = $this->input->post( "currency" );
			$vars['old_amount'] = $this->input->post( "amount" );
		}
		else{
			$vars['old_id'] = '';
			$vars['old_currency'] = '';
			$vars['old_amount'] = '';
		}
		$this->load->view("main_head",array("active"=>"withdraw"));
		$this->load->view("withdraw", $vars );
	}

	public function confirm_register(){
		$info = '';
		$successful = 1;
		if( $this->input->post() ){
			$open = $this->input->post( "open" );
			if( $open ){
				$successful = 2;
				$account = array();
				$account['stock_account'] = $this->input->post( "stock_account" );
				$account['trade_password'] = md5( $this->input->post( "trade_password" ) );
				$account['withdraw_password'] = md5( $this->input->post( "withdraw_password" ) );
				$account['id_card_number'] = $this->input->post( "id_card_number" );
				$account['customer_name'] = $this->input->post( "customer_name" );
				$trade_password1 = md5( $this->input->post( "trade_password1" ) );
				$withdraw_password1 = md5( $this->input->post( "withdraw_password1" ) );
				if( strlen($account['stock_account']) == 0 ){
					$info = "证券账户号为空。";
				}
				else if( strlen($this->input->post( "trade_password" ) ) == 0 ){
					$info = "交易密码为空。";
				}
				else if( strlen($this->input->post( "withdraw_password" ) ) == 0 ){
					$info = "取款密码为空。";
				}
				else if( $account['trade_password'] != $trade_password1 ){
					$info = "两次输入的交易密码不一致。";
				}
				else if( $account['withdraw_password'] != $withdraw_password1 ){
					$info = "两次输入的取款密码不一致。";
				}
				else if( strlen($account['id_card_number']) != 18 ){
					$info = "输入的身份证号不是18位。";
				}
				else if( strlen($account['customer_name']) == 0 ){
					$info = "客户姓名为空。";
				}
				else{
					$account_number = $this->funds_account->new_account( $account );
					$info = "开户成功，新的资金账户号为 ".$account_number;
					$successful = 3;
				}
			}
		}
		
		$this->load->view("main_head",array("active"=>"register"));
		$vars = array();
		$vars['info'] = $info;
		if( $successful == 2 ){
			$vars['old_stock_account'] = $this->input->post( "stock_account" );
			$vars['old_id_card_number'] = $this->input->post( "id_card_number" );
			$vars['old_customer_name'] = $this->input->post( "customer_name" );
		}
		else{
			$vars['old_stock_account'] = '';
			$vars['old_id_card_number'] = '';
			$vars['old_customer_name'] = '';
		}
		$this->load->view("confirm_register", $vars );
	}

	/*public function confirm_register() {
		$register_list 	= $this->funds_account_admin->get_register_list();
		if ($this->input->post()) {
			$confirm = $this->input->post("confirm");
			$id 	 = $this->input->post("id");
			$delete  = $this->input->post("delete");
			if ($confirm) {
				$this->funds_account_admin->handle_register($id, true);
				header("Location: " . base_url('index.php/admin/confirm_register'));
				return;
			}
			if ($delete) {
				// echo $delete;
				$this->funds_account_admin->handle_register($id, false);
				header("Location: " . base_url('index.php/admin/confirm_register'));
				return;
			}
		}
		$this->load->view("main_head",array("active"=>"register"));
		$this->load->view("confirm_register",array('users'=>$register_list));
	}*/

	public function confirm_lost() {
		$lost_list  = $this->funds_account_admin->get_lost_list();
		// echo var_dump($_POST);
		if ($this->input->post()) {
			$confirm = $this->input->post("confirm");
			$delete  = $this->input->post("delete");
			$id 	 = $this->input->post("id");						
			$reply   = $this->input->post("reply");
			if ($confirm) {
				$this->funds_account_admin->handle_lost_application($id,true, $reply);
				$this->funds_account->confirm_lost( $id, true );
				header("Location: " . base_url('index.php/admin/confirm_lost'));
				return;	
			}

			if ($delete) {
				$this->funds_account_admin->handle_lost_application($id,false,$reply);
				$this->funds_account->confirm_lost( $id, false );
				header("Location: " . base_url('index.php/admin/confirm_lost'));
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
			$reply   = $this->input->post("reply");
			$delete  = $this->input->post("delete");
			if ($confirm) {
				$this->funds_account_admin->handle_cancel_application($id,true, $reply);
				$this->funds_account->confirm_cancel( $id, true );
				header("Location: " . base_url('index.php/admin/confirm_cancel'));
				return;
			}
			if ($delete) {
				$this->funds_account_admin->handle_cancel_application($id,false, $reply);
				$this->funds_account->confirm_cancel( $id, false );
				header("Location: " . base_url('index.php/admin/confirm_cancel'));
				return;	
			}
		}

		$this->load->view('main_head', array('active'=>'cancel'));
		$this->load->view('confirm_cancel',array('users'=>$cancel_list));
	}


	public function display_fund_account() {
		if ($this->input->post()) {
			$id = $this->input->post('fund_account');
			$account = $this->funds_account->get_funds_account(array('id'=>$id));
			if ($account) {
				$account = $account[0];

				$currency_array = $this->funds_account->get_currency_array($id);
				$this->load->view('main_head', array('active'=>'display_fund_account'));
				$this->load->view('display_currency', array(
					'acc' => $account,
					'curs' => $currency_array
					));		
				return;		
			}
		}
		$this->load->view('main_head', array('active'=>'display_fund_account'));
		$this->load->view('display_fund_account');
	}

	public function logout() {
		$this->session->sess_destroy();
        header("Location: " . base_url()."index.php/admin/");
        return ;
	}

	public function log(){
		$logs = array();
		if ($this->input->post()) {
			$id = $this->input->post("id");
			$currency = $this->input->post("currency");
			$date1 = $this->input->post("date1");
			$date2 = $this->input->post("date2");
			$increase = $this->input->post("increase");

			$condition = array();
			if( strlen($id) > 0 ){
				$condition['funds_account'] = $id;
			}
			if( strlen($currency) > 0 ){
				$condition['currency'] = $currency;
			}
			if( strlen($date1) > 0 ){
				$condition['time >='] = $date1." 00:00:00";
			}
			if( strlen($date2) > 0 ){
				$condition['time <='] = $date2." 23:59:59";
			}
			if( $increase == 'increase' ){
				$condition['amount >'] = 0;
			}
			else if( $increase == 'decrease' ){
				$condition['amount <'] = 0;
			}
			//echo $condition['funds_account']."<br>".$condition['currency']."<br>".$condition['time like']."<br>".$condition['amount >']."<br>".$condition['amount <'];
			$logs = $this->funds_account_log_manager->get_log( $condition );
		}
		$this->load->view("main_head",array("active"=>"log"));
		$vars = array();
		$vars['logs'] = $logs;
		if ($this->input->post()){
			$vars['old_id'] = $this->input->post("id");
			$vars['old_currency'] = $this->input->post("currency");
			$vars['old_date1'] = $this->input->post("date1");
			$vars['old_date2'] = $this->input->post("date2");
			$vars['old_increase'] = $this->input->post("increase");
		}
		else{
			$vars['old_id'] = '';
			$vars['old_currency'] = '';
			$vars['old_date1'] = '';
			$vars['old_date2'] = '';
			$vars['old_increase'] = 'both';
		}
		$this->load->view("log", $vars );
	}

	//////////////////////////////////////////////////////////////////
	// private function 											//
	//////////////////////////////////////////////////////////////////
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