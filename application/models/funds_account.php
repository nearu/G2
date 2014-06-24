<?php

	class Funds_Account extends CI_Model  {

		function __construct() {
        	parent::__construct();
        	$this->load->database();
        }

        //得到账户的状态信息
        public function get_account_state( $id ){
        	$result = $this->db->get_where('funds_account', array('id'=>$id));
        	if( $result ){
        		$res = $result->result_array();
        		return $res[0]['state'];
        	}
			else{
				return "账户不存在。";
			}
        }

        //接受挂失请求或者拒绝挂失请求
        public function confirm_lost( $id, $is_accepted ){
        	if( $is_accepted ){
        		$this->db->where( 'id', $id );
				$account = array(
					'state' => 4
					);
				$this->db->update( 'funds_account', $account );
        	}
        	else{
        		$this->db->where( 'id', $id );
				$account = array(
					'state' => 0
					);
				$this->db->update( 'funds_account', $account );
        	}
        }

        //接受销户请求或者拒绝销户请求
        public function confirm_cancel( $id, $is_accepted ){
        	if( $is_accepted ){
        		$this->db->where( 'id', $id );
				$account = array(
					'state' => 3
					);
				$this->db->update( 'funds_account', $account );
        	}
        	else{
        		$this->db->where( 'id', $id );
				$account = array(
					'state' => 0
					);
				$this->db->update( 'funds_account', $account );
        	}
        }

        /**
		* 开户
		*/
		public function new_account($account) {
			$account['state'] = 0;
			$account['id'] = md5($account['id_card_number'] . $account['customer_name'] . time());
			$this->db->insert('funds_account', $account);
			return $account['id'];
		}

		/**
		* 存款
		* id 用户的主键
		* currency 是传入的字符串，下同
		* amount 是资金的变化量，下同
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function save($id, $currency, $amount) {
			$state = $this->get_account_state( $id );
			switch( $state ){
				case 1:
					return "该账户已申请销户，不能存款。";
				case 2:
					return "该账户已申请挂失，不能存款。";
				case 3:
					return "该账户已销户，不能存款。";
				case 4:
					return "该账户已挂失，不能存款。";
			}
			if ($amount <= 0) 
				return '存款数额需为正值。';
			return $this->modify_balance($id, $currency, $amount);
		}

		/**
		* 取款
		* 如果操作成功返回true，否则返回错误信息
		*/
		public function withdraw($id, $currency, $amount, $withdraw_password) {
			$state = $this->get_account_state( $id );
			switch( $state ){
				case 1:
					return "该账户已申请销户，不能取款。";
				case 2:
					return "该账户已申请挂失，不能取款。";
				case 3:
					return "该账户已销户，不能取款。";
				case 4:
					return "该账户已挂失，不能取款。";
			}
			$res = $this->verify_withdraw_pwd( $id, $withdraw_password );
			if( !( $res === true ) ){
				return $res;
			}
			if ($amount < 0) 
				return '取款数额不能为负。';
			return $this->modify_balance($id, $currency, -$amount);
		}

		/**
		* 验证交易密码
		* 返回 true / false
		*/ 
		public function verify_trade_pwd($id, $pwd) {
			$pwd = md5($pwd);
			$sql = "SELECT * FROM funds_account WHERE id='" . $id . "' AND trade_password='" . $pwd . "'";			
			$query = $this->db->query($sql);
			return ($query->num_rows() > 0);
		}

		/*
		* 验证取款密码
		* 返回 true / false
		*/ 
		public function verify_withdraw_pwd($id, $pwd) {
			$pwd = md5($pwd);
			$sql = "SELECT * FROM funds_account WHERE id='" . $id . "' AND withdraw_password='" . $pwd . "'";
			$query = $this->db->query($sql);
			if( $query->num_rows() > 0 ){
				return true;
			}
			else{
				return "取款密码错误。";
			}
		}

		/*
		* 修改交易密码
		* 返回 true / false
		*/
		public function change_trade_pwd($id, $old_pwd, $new_pwd) {
			$state = $this->get_account_state( $id );
			switch( $state ){
				case 1:
					return "该账户已申请销户，不能修改密码。";
				case 2:
					return "该账户已申请挂失，不能修改密码。";
				case 3:
					return "该账户已销户，不能修改密码。";
				case 4:
					return "该账户已挂失，不能修改密码。";
			}
			if ($this->verify_trade_pwd($id, $old_pwd) == false)
				return '交易密码错误。';
			$new_pwd = md5($new_pwd);
			$sql = "UPDATE funds_account SET trade_password='" . $new_pwd . "' WHERE id='" . $id . "'";
			$this->db->query($sql);
			return true;
		}

		/*
		* 修改取款密码
		* 返回 true / false
		*/
		public function change_withdraw_pwd($id, $old_pwd, $new_pwd) {
			$state = $this->get_account_state( $id );
			switch( $state ){
				case 1:
					return "该账户已申请销户，不能修改密码。";
				case 2:
					return "该账户已申请挂失，不能修改密码。";
				case 3:
					return "该账户已销户，不能修改密码。";
				case 4:
					return "该账户已挂失，不能修改密码。";
			}
			if ($this->verify_withdraw_pwd($id, $old_pwd) == false)
				return false;
			$new_pwd = md5($new_pwd);
			$sql = "UPDATE funds_account SET withdraw_password='" . $new_pwd . "' WHERE id='" . $id . "'";
			$this->db->query($sql);
			return true;	
		}

		/*
		* 验证币种是否正确
		* 返回 true / false
		*/ 
		public function verify_currency($currency) {
			// 所有合法币种
			$all_currency_type = array( 'CNY', 'USD', 'EUR', 'JPY', 'HKD', 'GBP', 'CAD', 'AUD', 'CHF', 'SGD' );
			return in_array($currency, $all_currency_type, true);
		}

		/**
		* 补办
		*/
		public function reapply($account, $new_trade_pwd, $new_withdraw_pwd) {
			$state = $this->get_account_state( $id );
			switch( $state ){
				case 1:
					return "该账户已申请销户，不能补办。";
				case 2:
					return "该账户已申请挂失，不能补办。";
				case 3:
					return "该账户已销户，不能补办。";
			}
			if (!$this->verify_trade_pwd($account['id'],$account['trade_password'])) {
				return '交易密码不正确';
			}
			if (!$this->verify_withdraw_pwd($account['id'],$account['withdraw_password'])) {
				return '取款密码不正确';
			}
			$old_account = $this->get_funds_account(array('id' => $account['id']));
			$old_account = $old_account[0];
			$old_account['trade_password'] 	 = $new_trade_pwd;
			$old_account['withdraw_password'] = $new_withdraw_pwd;
			unset($old_account['id']);
			$this->delete_account($account['id']);
			$this->new_account($old_account);
			return true;
		}


		// 申请挂失
		public function	report_loss($id) {
			$state = $this->get_account_state( $id );
			switch( $state ){
				case 1:
					return "该账户已申请销户，不能申请挂失。";
				case 2:
					return "该账户已申请挂失，不能重复申请挂失。";
				case 3:
					return "该账户已销户，不能申请挂失。";
				case 4:
					return "该账户已挂失，不能申请挂失。";
			}
			return $this->manage_report($id, 'lost_application');
		}

		// 申请销户
		public function report_cancel($id) {
			$state = $this->get_account_state( $id );
			switch( $state ){
				case 1:
					return "该账户已申请销户，不能重复申请销户。";
				case 2:
					return "该账户已申请挂失，不能申请销户。";
				case 3:
					return "该账户已销户，不能申请销户。";
				case 4:
					return "该账户已挂失，不能申请销户。";
			}
			if( $this->has_balance( $id ) ){
				return "该账户仍有余额，不能申请销户。";
			}
			return $this->manage_report($id, 'cancel_application');	
		}

		// 兑换货币
		public function exchange_currency($id, $withdraw_pwd, $currency_from, $currency_to, $amount) {
			$state = $this->get_account_state( $id );
			switch( $state ){
				case 1:
					return "该账户已申请销户，不能兑换货币。";
				case 2:
					return "该账户已申请挂失，不能兑换货币。";
				case 3:
					return "该账户已销户，不能兑换货币。";
				case 4:
					return "该账户已挂失，不能兑换货币。";
			}
			if( !( $this->verify_withdraw_pwd($id, $withdraw_pwd) === true ) ){
				return "取款密码不正确。";
			}
			if( !( $this->verify_currency($currency_from) === true ) || !( $this->verify_currency($currency_to) === true ) ){
				return "不支持的币种。";
			}
			$t = $this->get_balance($id, $currency_from);
			if ($t < $amount)
				return "余额不足。";
			$rate = $this->get_rate($currency_from, $currency_to);
			$this->modify_balance($id, $currency_from, -$amount);
			$this->modify_balance($id, $currency_to, $amount * $rate);
			return true;
		}

		/**
		* 检查交易
		* 返回 true / 错误信息
		*/
		public function check_trade($id, $currency, $amount, $trade_password) {
			if (!$this->verify_currency($currency)) 
				return '不支持的币种';
			if ($this->get_funds_account(array('id'=>$id)) === false) {
				return '账号不存在';
			}
			$result = $this->db->get_where('currency', array(
				'funds_account' => $id,
				'currency_type' => $currency,
				));
			if ($result->num_rows() == 0) {
				return '币种不存在';
			}
			$curr = $result->result_array();
			if ($curr[0]['balance'] < $amount) {
				return '余额不足';
			}
			if( !( $this->verify_trade_pwd( $id, $trade_password ) === true ) ){
				return '交易密码错误';
			}
			return true;
		}

		// 得到一个证券账户下所有资金账户
		public function get_acc_by_stock_acc($stock_account) {
			$result = $this->db->get_where('funds_account', array('stock_account' => $stock_account));
			return $result->result_array();
		}

		// 得到一个用户的所有信息
		// where 是使用的where条件
		public function get_funds_account($where) {
			$result = $this->db->get_where('funds_account', $where);
			if ($result->num_rows() == 0) return false;
			return $result->result_array();

		}


		public function get_currency_array($id) {
			$result = $this->db->get_where('currency', array('funds_account'=>$id));
			return $result->result_array();
		}

		//下面是李琛然LCR写的代码
		//都是面向中心交易系统的API，故都加上central前缀

		//中心交易系统冻结资金
		//输入：委托单号，资金账户，币种，金额（必须为正）
		public function central_freeze($order_number, $id, $currency, $amount) {
			//检查对应委托单号是否不存在
			$where = array('order_number' => $order_number);
			$query = $this->db->get_where('deputing_order',$where);
			if($query->num_rows() != 0){
				//已经存在这个委托单号了，出错
				return false;
			}
			$res = $this->manage_freeze($id, $currency, $amount, 'freeze');
			if($res === true ){//如果成功冻结
				$data =  array(
					'order_number' => $order_number,
					'funds_account' => $id,
					'total_frozen_money' => $amount,
					'used_money' => 0.0,
					'currency' => $currency
					);
				$this->db->insert( 'deputing_order', $data );
			}
			return $res;
		}

		//中心交易系统通知买股票成功，扣钱。
		//输入：委托单号，币种，金额（必须为正）
		public function central_spend_money($order_number, $currency, $amount) {
			$query = $this->db->get_where('deputing_order', array('order_number' => $order_number));
			if( $query->num_rows() == 0 ){//不存在这个委托单号，出错
				return false;
			}
			$this_order_array = $query->result_array();
			$this_order = $this_order_array[0];
			$id = $this_order['funds_account'];
			$total_frozen_money = $this_order['total_frozen_money'];
			$old_used_money = $this_order['used_money'];
			$old_balance = $total_frozen_money - $old_used_money;
			if( $amount > $old_balance ){//要扣的钱大于剩余的钱
				return false;
			}
			$new_used_money = $old_used_money + $amount;//更新已经用的钱的数额

			$this->db->where('order_number', $order_number);
			$this->db->update('deputing_order', array('used_money' => $new_used_money));//更新数据库


			$this->db->where('funds_account', $id);
			$this->db->where('currency_type', $currency);
			$this->db->update('currency', array('frozen_balance' => $total_frozen_money - $new_used_money));//更新数据库			

			return true;
		}

		//中心交易系统通知卖股票成功，加钱
		//输入：资金账户ID，币种，金额
		public function central_add_money($id, $currency, $amount) {
			return $this->modify_balance( $id, $currency, $amount );
		}

		//中心交易系统解冻资金
		//输入：委托单号，资金账户ID
		public function central_unfreeze($order_number) {
			$where = array(//检查对应委托单号是否存在
				'order_number' => $order_number
				);
			$query = $this->db->get_where('deputing_order',$where);
			if( $query->num_rows() == 0 ){//不存在这个委托单号，出错
				return false;
			}

			$this_order_array = $query->result_array();
			$this_order = $this_order_array[0];
			$id = $this_order['funds_account'];
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

		// 取得一个帐户某个币种的余额
		private function get_balance($id, $currency) {
			$sql = "SELECT * FROM currency WHERE funds_account='" . $id . "' AND currency_type='" . $currency . "'";
			$query = $this->db->query($sql);
			if ($query->num_rows() == 0)
				return 0;
			return $query->row()->balance;
		}

		//判断一个资金账户是否还有余额，即是否有资格申请销户
		private function has_balance( $id ){
			$result = $this->db->get_where( 'currency', array( 'funds_account' => $id ) );
			if( $result ){
				$result = $result->result_array();
				foreach( $result as $currency ){
					if( ( $currency['balance'] + $currency['frozen_balance'] ) != 0 ){
						return true;
					}
				}
				return false;
			}
			else{
				return false;
			}
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
				return '不支持该币种。';
			$where = array(
				'funds_account' => $id,
				'currency_type' => $currency,
				);
			if ($this->get_funds_account(array('id'=>$id)) === false) {
				return '账号不存在。';
			}
			$query = $this->db->get_where('currency',$where);
			if ($query->num_rows() == 0) {
				if ($amount < 0) {
					return '该账号下该币种不存在。';
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
					'funds_account' => $id,
					'currency' => $currency,
					'amount' => $amount,
					'balance' => $balance
					);
				$this->funds_account_log_manager->insert_log( $log );
			} else {
				$result = $this->db->select('balance')->get_where('currency', $where)->result_array();
				$pre_balance = $result[0]['balance'];
				if ($pre_balance + $amount < 0)
					return '该账户余额不足。';
				$this->db->where($where);
				$this->db->update('currency', array(
					'balance' => $pre_balance + $amount
					));

				$this->load->model('funds_account_log_manager');
				$balance = $pre_balance + $amount;
				$log = array(
					'funds_account' => $id,
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
				'funds_account' => $id,
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
			if( $type == "lost_application" ){
				$this->db->where( 'id', $id );
				$account = array(
					'state' => 2
					);
				$this->db->update( 'funds_account', $account );
			}
			else if( $type == "cancel_application" ){
				$this->db->where( 'id', $id );
				$account = array(
					'state' => 1
					);
				$this->db->update( 'funds_account', $account );
			}
			$this->db->insert($type, array(
					'funds_account' => $id,
					'state' => 0,
					'reply' => '',
					'time'  => date("Y-m-d H:i:s")
				));
			return true;
		}

		// 取得汇率
		private function get_rate($currency_from, $currency_to) {
			if ($this->verify_currency($currency_from) && $this->verify_currency($currency_to)) {
				$con = array('currency_from' => $currency_from, 'currency_to' => $currency_to);
				$res = $this->db->get_where('exchange_rate', $con)->row_array();
				return $res['rate'];
			}
			return false;
		}

		/**
		* 删除用户
		*/
		private function delete_account($id) {
			$this->db->delete('funds_account', array('id' => $id));
		}

		/**
		* 验证账户是否可用
		*/
		/*
		private function account_activte($id) {
			$b1 = $this->db->get_where('funds_account', array('id' => $id))->row_array();
			$b1 = ($b1['state'] != 0);
			return $b1;
		}*/
	}
?>