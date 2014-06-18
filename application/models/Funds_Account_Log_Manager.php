<?php
	class Funds_Account_Log_Manager extends CI_Model  {

		function __construct() {
        	parent::__construct();
        	$this->load->database();
        }

<<<<<<< HEAD

		public function insert_log($account, $log) {
			
		}

		public function get_log($account, $condition) {
			
=======
        //log是一个数组
		public function insert_log( $log ) {
			$this->db->insert( 'log', $log );
			return true;
		}

		//condition是一个数组
		public function get_log( $condition ) {
			$this->db->where( $condition );
			$query = $this->db->get( 'log' );

			return $query->result_array();
>>>>>>> 9df2d25f94ff80942db1e74208442d0a9742762b
		}
	}
?>