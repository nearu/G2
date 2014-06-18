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

 		// 测试：存钱
 		public function test_save() {
 			$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 			return $this->funds_account->save($id, 'CNY', 200);
 		}

 		// 测试：取钱
 		public function test_withdraw() {
 			$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 			$withdraw_password = '4567890123';
 			return $this->funds_account->withdraw($id,'CNY', 200, $withdraw_password);
 		}

 		// 测试：验证币种
 		public function test_verify_currency() {
 			return $this->funds_account->verify_currency('HKD');
 		}

 		// 测试：修改交易密码
 		public function test_change_trade_pwd() {
 			$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 			return $this->funds_account->change_trade_pwd($id, '1234567890', 'new_password') && 
 				$this->funds_account->change_trade_pwd($id, 'new_password', '1234567890');
 		}

 		// 测试：修改取款密码
 		public function test_change_withdraw_pwd() {
 			$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 			return $this->funds_account->change_withdraw_pwd($id, '4567890123', 'new_password') && 
 				$this->funds_account->change_withdraw_pwd($id, 'new_password', '4567890123');
 		}

 		// 测试：挂失
 		public function test_report_loss() {
 			$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 			return $this->funds_account->report_loss($id);
 		} 		

 		// 测试：销户
 		public function test_report_cancel() {
 			$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 			return $this->funds_account->report_cancel($id);
 		}

 		// 测试：货币兑换
 		public function test_exchange_currency() {
 			$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 			return $this->funds_account->exchange_currency($id, '4567890123', 'HKD', 'CNY', 0);
 		}

		// 测试：冻结
		public function test_freeze() {
			$id = '3d3ebea629b44c2a8d3650306c3a18d3';
 			$bool1 = $this->funds_account->freeze_all($id);
 			$bool2 = $this->funds_account->unfreeze_all($id);
 			$bool3 = $this->funds_account->freeze($id, 'HKD', 100);
 			$bool4 = $this->funds_account->unfreeze($id, 'HKD', 100);
 			return $bool1 && $bool2 && $bool3 && $bool4;
 		}

 		// 测试：补办
 		public function test_reapply() {

 		}
 		
 		// 测试：检查交易
		
		// 测试：查询证券账户下的所有资金账户
		public function test_get_acc_by_stock_acc() {
			$result = $this->funds_account->get_acc_by_stock_acc(1);
			return count($result) == 1;
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
 			$this->unit->run($this->unit_test_test(3), true, '单元测试可用');
 			$this->unit->run($this->test_new_account(), true, '开户');
 			$this->unit->run($this->test_save(), true, '存钱');
 			$this->unit->run($this->test_withdraw(), true, '取钱');
 			$this->unit->run($this->test_verify_currency(), true, '验证币种');
 			$this->unit->run($this->test_change_trade_pwd(), true, '修改交易密码');
 			$this->unit->run($this->test_change_withdraw_pwd(), true, '修改取款密码');
 			$this->unit->run($this->test_report_loss(), true, '挂失');
 			$this->unit->run($this->test_report_cancel(), true, '销户');
 			$this->unit->run($this->test_exchange_currency(), true, '货币兑换');
 			$this->unit->run($this->test_freeze(), true, '冻结');
 			$this->unit->run($this->test_get_acc_by_stock_acc(), true, '获取证券账户下的资金账户');
 			echo $this->unit->report();
 		}
 	}
?>