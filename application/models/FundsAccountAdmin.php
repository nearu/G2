<?php
	class FundsAccountAdmin extends CI_MODEL {

		// 管理员确认开户
		public function confirm_register($account) {

		}

		// 处理挂失
		// (acc, true/false, "info")
		public function handle_lost_application($account, $result, $reason) {

		}

		// 处理销户
		// (acc, true/false, "info")
		public function handle_cancel_application($account, $result, $reason) {

		}
	}
?>