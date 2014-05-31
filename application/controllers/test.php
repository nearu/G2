<?php
	class Test extends CI_Controller {
 		function __construct() {
  			parent::__construct();
 		}

 		public function test_account() {
 			$this->load->model('funds_account');
 			

 			// 测试 verify_currency
 			assert($this->funds_account->verify_currency('HKD'));
 			//assert($this->funds_account->verify_currency('USD'));
 			
 			// 测试 new_account
 			if (true) {
 				echo 'create new account';
	 			$acc = array(
	 				'stock_account' 	=> 1, 
	 				'trade_password' 	=> '123', 
	 				'withdraw_password' => '456',
	 				'id_card_number' 	=> '123456',
	 				'customer_name' 	=> 'aaa',
	 				'lost_state' 		=> 0,
	 				'cancel_state' 		=> 0);
	 			$this->funds_account->new_account($acc);
	 			assert($this->funds_account->get_funds_account(array(
	 					'customer_name' => $acc['customer_name']
	 				)));
 			}



 			// 测试 verify_trade_pwd
 			if (false) {
 				assert($this->funds_account->verify_trade_pwd('1', '123'));
 			}

 			// 测试 verify_withdraw_pwd
			if (false) {
 				assert($this->funds_account->verify_withdraw_pwd(1, '456'));
 			} 

 			// 测试 exchange_currency
			if (false) {
 				assert($this->funds_account->exchange_currency(1, '555', 'HKD', 'CNY', 0));
 			} 			

 			// 测试 change_trade_pwd
			if (false) {
 				assert($this->funds_account->change_trade_pwd(1, '123', '444'));
 			}

 			// 测试 change_withdraw_pwd
			if (false) {
 				assert($this->funds_account->change_withdraw_pwd(1, '456', '555'));
 			} 

 			////////////////////////////////////////////////////////////////////////
 			// cy的测试在下面													////
 			////////////////////////////////////////////////////////////////////////

 			// 测试save,withdraw,
 			if (false) {
 				$result = $this->funds_account->save(5,'CNY',200);
 				$this->printMsg('test save', $result);
 				assert($result == true);
				$result = $this->funds_account->withdraw(5,'HKD',600);
				$this->printMsg('test withdraw', $result);
 				assert($result == true);
 			}

 			// 测试freeze系列函数
 			if (false) {
 				$result = $this->funds_account->freeze_all(5);
 				$this->printMsg('test freeze_all', $result);
 				assert($result == true);
 			}

 			if (false) {
 				$result = $this->funds_account->unfreeze_all(5);
 				$this->printMsg('test unfreeze_all', $result);
 				assert($result == true);
 			}
 			if(false) {
				$result = $this->funds_account->freeze(5, 'HKD', 100);
 				$this->printMsg('test freeze', $result);
 				assert($result == true);
 			}
 			if(false) {
				$result = $this->funds_account->unfreeze(5, 'HKD', 100);
 				$this->printMsg('test unfreeze', $result);
 				assert($result == true);
 			}

 			// 测试提交申请挂失
			if(true) {
				$result = $this->funds_account->report_loss(5);
 				$this->printMsg('test report_loss', $result);
 				assert($result == true);
 			} 			
 			if(true) {
				$result = $this->funds_account->report_cancel(5);
 				$this->printMsg('test report_cancel', $result);
 				assert($result == true);
 			} 			
 		}



 		public function test_admin() {

 		}

 		public function test_log() {

 		}

 		public function index() {
 			echo 'Hello Test!';
 			$this->test_account();
 			$this->test_admin();
 			$this->test_log();
 		}


 		//////////////////////////////////
 		//util							//
 		//////////////////////////////////
 		private function printMsg($tag, $msg) {
 			echo '<br> '.$tag.':'.$msg.'<br>';
 		}
 	}
?>