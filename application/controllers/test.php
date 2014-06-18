<?php
	class Test extends CI_Controller {

 		function __construct() {
  			parent::__construct();
 		}

 		public function unit_test_test($x) {
 			return $x > 0;
 		}

 		// 测试：开户
 		public function test_new_account() {
 			$acc = array(
 				'stock_account' 	=> 1, 
 				'trade_password' 	=> md5('1234567890'), 
 				'withdraw_password' => md5('4567890123'),
 				'id_card_number' 	=> '123455432112345678',
 				'customer_name' 	=> '陈译',
 				'lost_state' 		=> 0,
 				'cancel_state' 		=> 0);
 			return $this->funds_account->get_funds_account(array('customer_name' => $acc['customer_name']));
 		}

 		// 测试：验证币种
 		public function test_verify_currency() {
 			return $this->funds_account->verify_currency('HKD');
 		}

 		// 测试：修改交易密码
 		public function test_change_trade_pwd() {
 			echo "测试：修改交易密码";
 			assert($this->funds_account->change_trade_pwd($id, '1234567890', 'new_password'));
 			assert($this->funds_account->change_trade_pwd($id, 'new_password', '1234567890'));
 		}

 		// 测试：修改取款密码
 		public function test_change_withdraw_pwd() {
 			echo "测试：修改取款密码";
 			assert($this->funds_account->change_withdraw_pwd($id, '4567890123', 'new_password'));
 			assert($this->funds_account->change_withdraw_pwd($id, 'new_password', '4567890123'));
 		}

 		// 测试：挂失
 		public function test_report_loss() {
 			echo "测试：挂失";
 			assert($this->funds_account->report_loss($id));
 		} 		

 		// 测试：补办
 		public function test_report_cancel() {
 			echo "测试：补办";
 			assert($this->funds_account->report_cancel($id));
 		}

 		// 测试：货币兑换
 		public function test_exchange_currency() {
 			assert($this->funds_account->exchange_currency($id, '4567890123', 'HKD', 'CNY', 0));
 		}

 		// 测试：销户

		// 测试：查询证券账户下的所有资金账户
		// 测试：检查交易
		// 测试：冻结资金
		// 测试freeze系列函数
		public function test_freeze() {
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
 		}
		// 测试：确认交易
		// 测试：插入交易记录


		// 以下测试由管理员界面完成
		// 测试：通过挂失申请
		// 测试：驳回挂失申请
		// 测试：通过销户申请
		// 测试：驳回销户申请
		// 测试：交易记录查询

 		public function index() {
 			$this->load->model('funds_account');
 			$this->load->library('unit_test');
 			$this->unit->run($this->unit_test_test(3), true, '单元测试可用', 'Yes!');
 			$this->unit->run($this->test_new_account(), true, '开户', 'Yes!');
 			$this->unit->run($this->test_verify_currency(), true, '验证币种', 'Yes!');
 			echo $this->unit->report();
 		}
 	}
?>