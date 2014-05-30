<?php
	class FundsAccount extends CI_MODEL {
		/**
		* 存款
		* account 是从AccountBuilder生成的，下同
		* currency 是传入的字符串，下同
		* amount 是资金的变化量，下同
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function save($account, $currency, $amount) {

		}

		/**
		* 取款
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function withdraw($account, $currency, $amount) {

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
		public function verify_trade_pwd($account) {
			$sql = 
		}

		// 验证取款密码
		public function verify_withdraw_pwd($account) {
			
		}

		// 新建资金账户
		public function new_account($account) {

		}
		
		// 验证币种是否正确
		public function verify_currency($currency) {
			// 所有合法币种
			$all_currency_type = array("CNY", "HKD", "EUR");
			return in_array($currency, $all_currency_type, true);
		}

		// 兑换货币
		public function exchange_currency($currency_a, $currency_b, $amount) {

		}

		// 修改密码
		public function change_trade_pwd($account, $new_pwd) {

		}

		public function change_withdraw_pwd($account, $new_pwd) {
			
		}
	}
?>