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
				return '存款不能为负！';
			return $this->modify_balance($id, $currency, $amount);
		}

		/**
		* 取款
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function withdraw($id, $currency, $amount) {
			if ($amount < 0) 
				return '取款不能为负！';
			return $this->modify_balance($id, $currency, -$amount);
		}

		/**
		* 冻结
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function freeze($id, $currency = NULL) {
			if ($this->get_user(array('id'=>$id)) === false) {
				return '该账户不存在';
			}
			$this->db->where('funds_account', $id);
			$this->db->update('currency', array(
				'is_frozen' => true,
				));
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
			$old_account['trade_password'] = $new_trade_pwd;
			$old_account['withdraw_password'] = $new_withdraw_pwd;
			unset($old_account['id']);
			$this->new_account($old_account);
			$this->delete_account(array(
				'id' => $account['id'],
				));
			return true;
		}


		// 申请挂失
		public function	report_loss($id) {

		}

		// 申请销户
		public function report_cancel($id) {

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
			$this->db->insert('funds_account', $account);
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
		// 1 不支持的币种
		// 2 币种不存在
		// 3 余额不足
		// 4 账户冻结中
		// 5 账号不存在
		public function check_trade($id, $currency, $amount) {
			if (!$this->verify_currency($currency)) 
				return '1';
			if ($this->get_user(array('id'=>$id)) === false) {
				return '5';
			}
			$result = $this->db->get_where('currency', array(
				'funds_account' => $id,
				'currency_type' => $currency,
				));
			if ($result->num_rows() == 0) {
				return '2';
			}
			$curr = $result->result_array();
			if ($curr['is_frozen'] == true) {
				return '4';
			}
			if ($curr['balance'] < $amount) {
				return '3';
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


		// ---------------------------------------------------------------------------
		// Private Functions
		// ---------------------------------------------------------------------------

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
		// 1 该不支持币种
		// 2 该账号下币种不存在
		// 3 该账户余额不足
		// 4 账户冻结中
		// 5 账号不存在
		private function modify_balance($id, $currency, $amount) {
			if (!$this->verify_currency($currency)) 
				return '1';
			$where = array(
				'funds_account' => $id,
				'currency_type' => $currency,
				);
			if ($this->get_user(array('id'=>$id)) === false) {
				return '5';
			}
			$query = $this->db->get_where('currency',$where);
			if ($query->num_rows() == 0) {
				return '2';
			} else {
				$result = $this->db->select('balance')->get_where('currency', $where)->result_array();
				$pre_balance = $result[0]['balance'];
				if ($pre_balance + $amount < 0)
					return '3';
				$this->db->where($where);
				$this->db->update('currency', array(
					'balance' => $pre_balance + $amount
					));
			}
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