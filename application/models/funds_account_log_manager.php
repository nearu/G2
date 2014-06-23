<?php
	class Funds_Account_Log_Manager extends CI_Model  {

		function __construct() {
        	parent::__construct();
        	$this->load->database();
        }

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
		}
	}
?>