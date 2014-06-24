CREATE TABLE funds_account (
	id					char(32),
	stock_account		varchar(128),
	trade_password		varchar(128),
	withdraw_password	varchar(128),
	id_card_number 		varchar(128),
	customer_name 		varchar(128),
	state 				int,
	#  0,正常 1,销户申请中 2,挂失申请中 3,已销户 4,已挂失，待补办
	primary key(id)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE lost_application (
	funds_account 		char(32) NOT NULL,
	state				int,
	reply				text,
	time 				date,
	FOREIGN KEY (funds_account) REFERENCES funds_account(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE cancel_application (
	funds_account 		char(32) NOT NULL,
	state				int,
	reply				text,
	time 				date,
	FOREIGN KEY (funds_account) REFERENCES funds_account(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE currency (
	funds_account 	char(32) NOT NULL,
	currency_type	varchar(32),
	balance 		double,
	frozen_balance 	double,
	FOREIGN KEY (funds_account) REFERENCES funds_account(id) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE log (
	funds_account 	varchar(128),
	currency 		 		varchar(32),
	amount					double,#变动的钱数额（可以为负）
	balance 				double,#变动以后的余额
	time					timestamp DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (funds_account) REFERENCES funds_account(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE exchange_rate (
	currency_from	varchar(128),
	currency_to		varchar(128),
	rate 			double
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE admin (
	id 			int NOT NULL,
	name 		varchar(128),
	password 	varchar(128),
	primary key(id)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


#存储所有中心交易系统委托订单
CREATE TABLE deputing_order (
	order_number 			varchar(128), 	#委托单号
	funds_account 			char(32) NOT NULL,
	total_frozen_money 		double, 	#一开始被冻结的钱（这个值一直不变）
	used_money 				double, 			#已经用掉的冻结的钱
	currency 				varchar(128), 		#币种
	primary key (order_number)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

INSERT INTO exchange_rate VALUES ('HKD', 'CNY', 0.8);
INSERT INTO exchange_rate VALUES ('CNY', 'HKD', 1.25);

INSERT INTO admin VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3');
