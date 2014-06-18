<?php

	class Funds_Account extends CI_Model  {

		function __construct() {
        	parent::__construct();
        	$this->load->database();
        }

		/**
		* 存款
		* id 用户的主键
		* currency 是传入的字符串，下同
		* amount 是资金的变化量，下同
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function save($id, $currency, $amount) {
			if ($amount < 0) 
				return '存款数额不能为负！';
			return $this->modify_balance($id, $currency, $amount);
		}

		/**
		* 取款
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function withdraw($id, $currency, $amount, $withdraw_password) {
			$res = $this->check_withdraw_password( $id, $withdraw_password );
			if( !( $res === true ) ){
				return $res;
			}
			if ($amount < 0) 
				return '取款数额不能为负！';
			return $this->modify_balance($id, $currency, -$amount);
		}

		/**
		* 冻结该账户下所有的资金
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function freeze_all($id) {
			return $this->manage_freeze_all($id, 'freeze');
		}
		// 冻结某个账户的特定币种，amount为冻结的资金
		public function freeze($id, $currency, $amount) {
			return $this->manage_freeze($id, $currency,$amount,'freeze');
		}

		public function unfreeze_all($id) {
			return $this->manage_freeze_all($id, 'unfreeze');

		}

		public function unfreeze($id, $currency, $amount) {
			return $this->manage_freeze($id, $currency,$amount,'unfreeze');
		}


		/**
		* 确认交易
		* 传入amount有正负，代表增加余额(+)或减少余额(-)
		*/
		public function confirm_trade($id, $currency, $amount) {
			return $this->modify_balance($id, $currency, $amount);
		}

		/**
		* 重新开户
		* 
		*/
		public function reapply($account, $new_trade_pwd, $new_withdraw_pwd) {
			if (!$this->verify_trade_pwd($account['id'],$account['trade_password'])) {
				return '交易密码不正确';
			}
			if (!$this->verify_withdraw_pwd($account['id'],$account['withdraw_password'])) {
				return '取款密码不正确';
			}
			$old_account = get_funds_account($account['id']);
			$old_account[0]['trade_password'] 	 = $new_trade_pwd;
			$old_account[0]['withdraw_password'] = $new_withdraw_pwd;
			unset($old_account['id']);
			$this->new_account($old_account);
			$this->delete_account(array(
				'id' => $account['id'],
				));
			return true;
		}


		// 申请挂失
		// lost_state ： 
		// 0 表示正在申请挂失，
		// 1 表示已经在审核状态
		// 2 表示已经挂失成功
		public function	report_loss($id) {
			return $this->manage_report($id, 'lost_application');
		}

		// 申请销户
		public function report_cancel($id) {
			return $this->manage_report($id, 'cancel_application');	
		}

		// 验证交易密码
		// 返回 true / false
		public function verify_trade_pwd($id, $pwd) {
			$sql = "SELECT * FROM funds_account WHERE id='" . $id . "' AND trade_password='" . $pwd . "'";			
			$query = $this->db->query($sql);
			return ($query->num_rows() > 0);
		}

		// 验证取款密码
		// 返回 true / false
		public function verify_withdraw_pwd($id, $pwd) {
			$sql = "SELECT * FROM funds_account WHERE id='" . $id . "' AND withdraw_password='" . $pwd . "'";
			$query = $this->db->query($sql);
			return ($query->num_rows() > 0);
		}

		// 新建资金账户
		public function new_account($account) {
			$account['create_state'] = 0;
			$account['id'] = md5($account['id_card_number'] . $account['customer_name']);
			$this->db->insert('funds_account', $account);
			return $account['id'];
		}
		
		// 验证币种是否正确
		public function verify_currency($currency) {
			// 所有合法币种
			$all_currency_type = array("CNY", "HKD", "EUR");
			return in_array($currency, $all_currency_type, true);
		}

		// 兑换货币
		public function exchange_currency($id, $withdraw_pwd, $currency_from, $currency_to, $amount) {
			if (!$this->verify_withdraw_pwd($id, $withdraw_pwd) || 
				!$this->verify_currency($currency_from) || 
				!$this->verify_currency($currency_to))
				return false;
			$t = $this->get_balance($id, $currency_from);
			if ($t === false || $t < $amount)
				return false;
			$rate = $this->get_rate($currency_from, $currency_to);
			$this->modify_balance($id, $currency_from, -$amount);
			$this->modify_balance($id, $currency_to, $amount * $rate);
			return true;
		}

		// 修改交易密码
		// 返回 true / false
		public function change_trade_pwd($id, $old_pwd, $new_pwd) {
			if ($this->verify_trade_pwd($id, $old_pwd) == false)
				return false;
			$sql = "UPDATE funds_account SET trade_password='" . $new_pwd . "' WHERE id='" . $id . "'";
			$this->db->query($sql);
			return true;
		}

		// 修改取款密码
		// 返回 true / false
		public function change_withdraw_pwd($id, $old_pwd, $new_pwd) {
			if ($this->verify_withdraw_pwd($id, $old_pwd) == false)
				return false;
			$sql = "UPDATE funds_account SET withdraw_password='" . $new_pwd . "' WHERE id='" . $id . "'";
			$this->db->query($sql);
			return true;	
		}

		// 检查币种存在与否，以及余额是否足够
		// 都满足，返回true
		// 否则返回错误信息：
		// e1 不支持的币种
		// e2 币种不存在
		// e3 余额不足
		// e4 账户的该币种被完全冻结中
		// e5 账号不存在
		public function check_trade($id, $currency, $amount) {
			if (!$this->verify_currency($currency)) 
				return 'e1';
			if ($this->get_funds_account(array('id'=>$id)) === false) {
				return 'e5';
			}
			$result = $this->db->get_where('currency', array(
				'funds_account' => $id,
				'currency_type' => $currency,
				));
			if ($result->num_rows() == 0) {
				return 'e2';
			}
			$curr = $result->result_array();
			if ($curr[0]['balance'] < $amount) {
				return 'e3';
			}
			return true;
		}


		// 得到一个用户的所有信息
		// where 是使用的where条件
		public function get_funds_account($where) {
			$result = $this->db->get_where('funds_account', $where);
			if ($result->num_rows() == 0) return false;
			return $result->result_array();

		}

		//下面是李琛然LCR写的代码
		//都是面向中心交易系统的API，故都加上central前缀

		//中心交易系统冻结资金
		//输入：委托单号，资金账户，币种，金额（必须为正）
		public function central_freeze( $order_number, $id, $currency, $amount ){
			$where = array(//检查对应委托单号是否不存在
				'order_number' => $order_number
				);
			$query = $this->db->get_where('deputing_order',$where);
			if( $query->num_rows() != 0 ){//已经存在这个委托单号了，出错
				return false;
			}

			if( $this->manage_freeze( $id, $currency, $amount, 'freeze' ) === true ){//如果成功冻结
				$data =  array(
					'order_number' => $order_number,
					'total_frozen_money' => $amount,
					'used_money' => 0.0,
					'currency' => $currency
					);
				$this->db->insert( 'deputing_order', $data );
				return true;
			}
			else{//失败返回false
				return false;
			}
		}

		//中心交易系统通知买股票成功，扣钱。
		//输入：委托单号，资金账户，币种，金额（必须为正）
		public function central_spend_money( $order_number, $id, $currency, $amount ){
			$where = array(//检查对应委托单号是否存在
				'order_number' => $order_number
				);
			$query = $this->db->get_where('deputing_order',$where);
			if( $query->num_rows() == 0 ){//不存在这个委托单号，出错
				return false;
			}

			$this_order_array = $query->result_array();
			$this_order = $this_order_array[0];
			$total_frozen_money = $this_order['total_frozen_money'];
			$old_used_money = $this_order['used_money'];
			$old_balance = $total_frozen_money - $old_used_money;
			if( $amount > $old_balance ){//要扣的钱大于剩余的钱
				return false;
			}

			$new_used_money = $old_used_money + $amount;//更新已经用的钱的数额

			$this->db->where( 'order_number', $order_number );
			$data = array(
				'used_money' => $new_used_money
				);
			$this->db->update( 'deputing_order', $data );//更新数据库

			return true;
		}

		//中心交易系统通知卖股票成功，加钱
		//输入：资金账户ID，币种，金额
		public function central_add_money( $id, $currency, $amount ){
			return $this->modify_balance( $id, $currency, $amount );
		}

		//中心交易系统解冻资金
		//输入：委托单号，资金账户ID
		public function central_unfreeze( $order_number, $id ){
			$where = array(//检查对应委托单号是否存在
				'order_number' => $order_number
				);
			$query = $this->db->get_where('deputing_order',$where);
			if( $query->num_rows() == 0 ){//不存在这个委托单号，出错
				return false;
			}

			$this_order_array = $query->result_array();
			$this_order = $this_order_array[0];
			$total_frozen_money = $this_order['total_frozen_money'];
			$used_money = $this_order['used_money'];
			$left_money = $total_frozen_money - $used_money;
			$currency = $this_order['currency'];

			$this->db->where( 'order_number', $order_number );
			$data = array(
				'total_frozen_money' => 0.0,
				'used_money' => 0.0
				);
			$this->db->update( 'deputing_order', $data );//更新数据库

			if( ! ( $this->manage_freeze( $id, $currency, $total_frozen_money, 'unfreeze' ) === true ) ){
				//先解冻钱
				return false;
			}
			if( ! ( $this->modify_balance( $id, $currency, -$used_money ) === true ) ){
				//再把钱扣掉
				return false;
			}
			if( $left_money > 0 ){
				//钱没花完，也就是钱数增加了，需要打日志
			}
			
			return true;
		}

		//以上是李琛然写的代码


		// ---------------------------------------------------------------------------
		// Private Functions
		// ---------------------------------------------------------------------------

		private function check_withdraw_password( $id, $withdraw_password ){
			$where = array(
				'id' => $id
				);
			$query = $this->db->get_where( 'funds_account', $where );
			if( $query->num_rows() == 0 ){
				return '不存在这个账户';
			}

			$res = $query->result_array();
			$real_password = $res[0]['withdraw_password'];

			if( md5($withdraw_password) == $real_password ){
				return true;
			}
			else{
				return '密码错误';
			}
		}

		// 取得一个帐户某个币种的余额
		private function get_balance($id, $currency) {
			if (!$this->verify_currency($currency))
				return false;
			$sql = "SELECT * FROM currency WHERE funds_account='" . $id . "' AND currency_type='" . $currency . "'";
			$query = $this->db->query($sql);
			if ($query->num_rows() == 0)
				return 0;
			return $query->row()->balance;
		}

		// 给某个帐户的某个币种增加/减少钱
		// 如果正确的完成修改，返回true，否则返回错误信息：
		// e1 该不支持币种
		// e2 该账号下币种不存在
		// e3 该账户余额不足
		// e4 账户冻结中
		// e5 账号不存在
		private function modify_balance($id, $currency, $amount) {
			if (!$this->verify_currency($currency)) 
				return 'e1';
			$where = array(
				'funds_account' => $id,
				'currency_type' => $currency,
				);
			if ($this->get_funds_account(array('id'=>$id)) === false) {
				return 'e5';
			}
			$query = $this->db->get_where('currency',$where);
			if ($query->num_rows() == 0) {
				if ($amount < 0) {
					return 'e2';
				}
				$this->db->insert('currency', array(
						'funds_account' 	=> $id,
						'currency_type' 	=> $currency,
						'balance'			=> $amount,
						'frozen_balance' 	=> 0
					));
				$this->load->model('funds_account_log_manager');
				$balance = $amount;
				$log = array(
					'funds_account_number' => $id,
					'currency' => $currency,
					'amount' => $amount,
					'balance' => $balance
					);
				$this->funds_account_log_manager->insert_log( $log );
			} else {
				$result = $this->db->select('balance')->get_where('currency', $where)->result_array();
				$pre_balance = $result[0]['balance'];
				if ($pre_balance + $amount < 0)
					return 'e3';
				$this->db->where($where);
				$this->db->update('currency', array(
					'balance' => $pre_balance + $amount
					));

				$this->load->model('funds_account_log_manager');
				$balance = $pre_balance + $amount;
				$log = array(
					'funds_account_number' => $id,
					'currency' => $currency,
					'amount' => $amount,
					'balance' => $balance
					);
				$this->funds_account_log_manager->insert_log( $log );
			}
			return true;
		}

		private function manage_freeze_all($id, $type) {
			if ($this->get_funds_account(array('id'=>$id)) === false) {
				return '该账户不存在';
			}
			$result = $this->db->get_where('currency', array(
				'funds_account' => $id,
				));
			$num = $result->num_rows();
			$curr_array = $result->result_array();
			for($i = 0; $i < $num; $i++) {				
				$info = $this->manage_freeze($id,$curr_array[$i]['currency_type'], 
					$type == 'freeze' ? $curr_array[$i]['balance'] : $curr_array[$i]['frozen_balance'],
					$type);
				if ($info != true)	{
					return $info;
				}
			}
			return true;
		}

		private function manage_freeze($id, $currency, $amount, $type) {
			if (!$this->verify_currency($currency)) 
				return '不支持的币种';
			if ($this->get_funds_account(array('id'=>$id)) === false) {
				return '该账户不存在';
			}
			$result = $this->db->get_where('currency', array(
				'funds_account' => $id,
				'currency_type' => $currency,
				));
			if ($result->num_rows() == 0) {
				return '没有的对应的币种';
			}
			$curr = $result->result_array();
			$old_balance = $curr[0]['balance'];
			$old_frozen_balance = $curr[0]['frozen_balance'];
			$new_balance = 0;
			$new_frozen_balance = 0;
			if($type == 'freeze') {
				$new_balance = $old_balance-$amount;
				$new_frozen_balance = $old_frozen_balance + $amount;
			} else {
				$new_balance = $old_balance + $amount;
				$new_frozen_balance = $old_frozen_balance - $amount;
			}
			if ($new_balance < 0) {
				return '想冻结的数额大于余额!';
			}
			if ($new_frozen_balance < 0) {
				return '想解冻的数额大于已冻结数额！';
			}
			$this->db->where(array(
				'funds_account' => $id,
				'currency_type' => $currency,
				))->update('currency', array(
					'balance' => $new_balance,
					'frozen_balance' => $new_frozen_balance,
				));

			$this->load->model('funds_account_log_manager');
			$balance = $new_balance;
			$amount = 0;
			if( $type == 'freeze' ){
				$amount = -$amount;
			}
			$log = array(
				'funds_account_number' => $id,
				'currency' => $currency,
				'amount' => $amount,
				'balance' => $balance
				);
			$this->funds_account_log_manager->insert_log( $log );
			return true;
		}

		////// 这里还没有更新account里的状态，需要协商下具体的状态
		private function manage_report($id, $type) {
			if ($this->get_funds_account(array('id'=>$id)) === false) {
				return '该账户不存在';
			}
			$this->db->insert($type, array(
					'funds_account' => $id,
					'state' => 0,
					'reply' => '',
					'time'  => date("Y-m-d H:i:s")
				));
			return true;
		}

		private function delete_user($where) {
			$this->db->delete('funds_account', $where);
		}

		// 取得汇率
		private function get_rate($currency_from, $currency_to) {
			return 1;
		}
	}
?>