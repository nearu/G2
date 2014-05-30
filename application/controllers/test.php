<?php
	class test extends CI_Controller {
 		function __construct() {
  			parent::__construct();
 		}

 		public function test_account() {
 			$this->load->model('FundsAccount');
 			// 测试币种检测
 			assert($this->FundsAccount->verify_currency('HKD'));
 			//assert($this->FundsAccount->verify_currency('USD'));
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