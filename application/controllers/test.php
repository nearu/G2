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
 			if (false) {
	 			$acc = array(
	 				'stock_account' => 1, 
	 				'trade_password' => '123', 
	 				'withdraw_password' => '456',
	 				'id_card_number' => '123456',
	 				'customer_name' => 'aaa',
	 				'lost_state' => 0,
	 				'cancel_state' => 0);
	 			$this->funds_account->new_account($acc);
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
			if (true) {
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
 	}
?>