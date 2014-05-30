<?php
	class FundsAccount extends CI_MODEL {

		function __construct() {
        	parent::__construct();
        	$this->load->database();
        }

		/**
		* 存款
		* account 是从AccountBuilder生成的，下同
		* currency 是传入的字符串，下同
		* amount 是资金的变化量，下同
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function save($account, $currency, $amount) {
			if (ok)
				$this->modify_balance($id, $currency, $amount);
		}

		/**
		* 取款
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function withdraw($account, $currency, $amount) {
			if (ok)
				$this->modify_balance($id, $currency, -$amount);
		}

		/**
		* 冻结
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function freeze($account) {

		}

		/**
		* 确认交易
		* 传入amount有正负，代表增加余额(+)或减少余额(-)
		*/
		public function confirm_trade($account, $currency, $amount) {

		}

		/**
		* 重新开户
		* 
		*/
		public function reapply($account, $new_trade_pwd, $new_withdraw_pwd) {

		}

		// 申请挂失
		public function	report_loss($account) {

		}

		// 申请销户
		public function report_cancel($account) {

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
		private function modify_balance($id, $currency, $amount) {

		}

		// 取得汇率
		private function get_rate($currency_from, $currency_to) {
			return 1;
		}
	}
?>