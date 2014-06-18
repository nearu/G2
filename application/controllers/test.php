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
 			
 			// 测试：开户
 			if (false) {
 				echo '测试：开户';
	 			$acc = array(
	 				'stock_account' 	=> 1, 
	 				'trade_password' 	=> md5('1234567890'), 
	 				'withdraw_password' => md5('4567890123'),
	 				'id_card_number' 	=> '123455432112345678',
	 				'customer_name' 	=> '陈译',
	 				'lost_state' 		=> 0,
	 				'cancel_state' 		=> 0);
	 			echo $this->funds_account->new_account($acc);
	 			assert($this->funds_account->get_funds_account(array(
	 					'customer_name' => $acc['customer_name']
	 				)));
 			}

 			// 测试：存钱
 			if (false) {
 				echo '测试：存钱';
 				$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 				$result = $this->funds_account->save($id,'CNY',200);
 				//$this->printMsg('test save', $result);
 				assert($result == true);
 			}


 			// 测试：修改交易密码
			if (true) {
				echo "测试：修改交易密码";
				$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 				assert($this->funds_account->change_trade_pwd($id, '1234567890', 'new_password'));
 			}

 			// 测试：修改取款密码
			if (false) {
				echo "测试：修改取款密码";
				$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 				assert($this->funds_account->change_withdraw_pwd($id, '4567890123', 'new_password'));
 			}

 			if( false ){
 				//先新建资金账户
 				echo 'create new account';
	 			$acc = array(
	 				'stock_account_number' 	=> 1, 
	 				'trade_password' 	=> '123', 
	 				'withdraw_password' => '456',
	 				'customer_name' 	=> '陈译',
	 				'lost_state' 		=> 0,
	 				'cancel_state' 		=> 0);
	 			//$this->funds_account->new_account($acc);

	 			$this_account;
	 			assert($this_account = $this->funds_account->get_funds_account(array(
	 					'customer_name' => $acc['customer_name']
	 				)));

	 			$id = $this_account[0]['id'];

	 			//存钱
	 			//$result = $this->funds_account->save( $id,'CNY',200);
 				//$this->printMsg('test save', $result);

 				$order_number = '8889';
 				$currency = 'CNY';
 				//assert( $this->funds_account->central_freeze( $order_number, $id, $currency, 1000 ) );
 				//assert( $this->funds_account->central_spend_money( $order_number, $id, $currency, 5000 ) );
 				assert( $this->funds_account->central_unfreeze( $order_number, $id ) );

 				//assert( $this->funds_account->central_add_money( $id, $currency, 500 ) );
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

 			

 			////////////////////////////////////////////////////////////////////////
 			// cy的测试在下面													////
 			////////////////////////////////////////////////////////////////////////

 			

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