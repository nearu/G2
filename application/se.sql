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

INSERT INTO exchange_rate VALUES ('USD', 'CNY', 6.21);
INSERT INTO exchange_rate VALUES ('USD', 'SGD', 1.24);
INSERT INTO exchange_rate VALUES ('USD', 'CHF', 0.89);
INSERT INTO exchange_rate VALUES ('USD', 'HKD', 7.75);
INSERT INTO exchange_rate VALUES ('USD', 'EUR', 0.73);
INSERT INTO exchange_rate VALUES ('USD', 'GBP', 0.58);
INSERT INTO exchange_rate VALUES ('USD', 'AUD', 1.06);
INSERT INTO exchange_rate VALUES ('USD', 'JPY', 101.6);
INSERT INTO exchange_rate VALUES ('USD', 'CAD', 1.07);
INSERT INTO exchange_rate VALUES ('USD', 'USD', 1);

INSERT INTO exchange_rate VALUES ('HKD', 'CNY', 0.8);
INSERT INTO exchange_rate VALUES ('HKD', 'USD', 0.129);
INSERT INTO exchange_rate VALUES ('HKD', 'GBP', 0.075);
INSERT INTO exchange_rate VALUES ('HKD', 'AUD', 0.1366);
INSERT INTO exchange_rate VALUES ('HKD', 'YPY', 13.11);
INSERT INTO exchange_rate VALUES ('HKD', 'EUR', 0.094);
INSERT INTO exchange_rate VALUES ('HKD', 'CHF', 0.1145);
INSERT INTO exchange_rate VALUES ('HKD', 'SGD', 0.1609);
INSERT INTO exchange_rate VALUES ('HKD', 'CAD', 0.1377);
INSERT INTO exchange_rate VALUES ('HKD', 'HKD', 1);

INSERT INTO exchange_rate VALUES ('CAD', 'CNY', 5.813);
INSERT INTO exchange_rate VALUES ('CAD', 'HKD', 7.3);
INSERT INTO exchange_rate VALUES ('CAD', 'EUR', 0.68);
INSERT INTO exchange_rate VALUES ('CAD', 'USD', 9.3);
INSERT INTO exchange_rate VALUES ('CAD', 'GBP', 0.54);
INSERT INTO exchange_rate VALUES ('CAD', 'AUD', 0.99);
INSERT INTO exchange_rate VALUES ('CAD', 'JPY', 95.2);
INSERT INTO exchange_rate VALUES ('CAD', 'CHF', 0.83);
INSERT INTO exchange_rate VALUES ('CAD', 'SGD', 1.17);
INSERT INTO exchange_rate VALUES ('CAD', 'CAD', 1);

INSERT INTO exchange_rate VALUES ('CNY', 'HKD', 1.25);
INSERT INTO exchange_rate VALUES ('CNY', 'EUR', 0.117);
INSERT INTO exchange_rate VALUES ('CNY', 'AUD', 0.17);
INSERT INTO exchange_rate VALUES ('CNY', 'JPY', 16.37);
INSERT INTO exchange_rate VALUES ('CNY', 'GBP', 0.094);
INSERT INTO exchange_rate VALUES ('CNY', 'CAD', 0.172);
INSERT INTO exchange_rate VALUES ('CNY', 'CHF', 0.143);
INSERT INTO exchange_rate VALUES ('CNY', 'SGD', 0.201);
INSERT INTO exchange_rate VALUES ('CNY', 'USD', 0.161);
INSERT INTO exchange_rate VALUES ('CNY', 'CNY', 1);

INSERT INTO exchange_rate VALUES ('EUR', 'CNY', 8.5);
INSERT INTO exchange_rate VALUES ('EUR', 'HKD', 10.6);
INSERT INTO exchange_rate VALUES ('EUR', 'USD', 1.37);
INSERT INTO exchange_rate VALUES ('EUR', 'JPY', 139);
INSERT INTO exchange_rate VALUES ('EUR', 'GBP', 0.80);
INSERT INTO exchange_rate VALUES ('EUR', 'CAD', 1.46);
INSERT INTO exchange_rate VALUES ('EUR', 'CHF', 1.21);
INSERT INTO exchange_rate VALUES ('EUR', 'SGD', 1.70);
INSERT INTO exchange_rate VALUES ('EUR', 'AUD', .37);
INSERT INTO exchange_rate VALUES ('EUR', 'EUR', 1);

INSERT INTO exchange_rate VALUES ('JPY', 'CNY', 0.061);
INSERT INTO exchange_rate VALUES ('JPY', 'HKD', 0.076);
INSERT INTO exchange_rate VALUES ('JPY', 'USD', 0.009);
INSERT INTO exchange_rate VALUES ('JPY', 'EUR', 0.007);
INSERT INTO exchange_rate VALUES ('JPY', 'GBP', 0.005);
INSERT INTO exchange_rate VALUES ('JPY', 'CAD', 0.0105);
INSERT INTO exchange_rate VALUES ('JPY', 'CHF', 0.0087);
INSERT INTO exchange_rate VALUES ('JPY', 'SGD', 0.0123);
INSERT INTO exchange_rate VALUES ('JPY', 'AUD', 0.104);
INSERT INTO exchange_rate VALUES ('JPY', 'JPY', 1);

INSERT INTO exchange_rate VALUES ('GBP', 'CNY', 10.6);
INSERT INTO exchange_rate VALUES ('GBP', 'HKD', 13.3);
INSERT INTO exchange_rate VALUES ('GBP', 'USD', 1.71);
INSERT INTO exchange_rate VALUES ('GBP', 'EUR', 1.25);
INSERT INTO exchange_rate VALUES ('GBP', 'JPY', 174);
INSERT INTO exchange_rate VALUES ('GBP', 'CAD', 1.82);
INSERT INTO exchange_rate VALUES ('GBP', 'CHF', 1.52);
INSERT INTO exchange_rate VALUES ('GBP', 'SGD', 2.13);
INSERT INTO exchange_rate VALUES ('GBP', 'AUD', 1.81);
INSERT INTO exchange_rate VALUES ('GBP', 'GBP', 1);

INSERT INTO exchange_rate VALUES ('SGD', 'CNY', 4.9);
INSERT INTO exchange_rate VALUES ('SGD', 'HKD', 6.2);
INSERT INTO exchange_rate VALUES ('SGD', 'USD', 0.80);
INSERT INTO exchange_rate VALUES ('SGD', 'EUR', 0.59);
INSERT INTO exchange_rate VALUES ('SGD', 'JPY', 81.5);
INSERT INTO exchange_rate VALUES ('SGD', 'CAD', 0.86);
INSERT INTO exchange_rate VALUES ('SGD', 'CHF', 0.71);
INSERT INTO exchange_rate VALUES ('SGD', 'GBP', 0.47);
INSERT INTO exchange_rate VALUES ('SGD', 'AUD', 0.84);
INSERT INTO exchange_rate VALUES ('SGD', 'SGD', 1);

INSERT INTO exchange_rate VALUES ('AUD', 'CNY', 5.861);
INSERT INTO exchange_rate VALUES ('AUD', 'USD', 0.945);
INSERT INTO exchange_rate VALUES ('AUD', 'EUR', 0.6907);
INSERT INTO exchange_rate VALUES ('AUD', 'JPY', 95.9867);
INSERT INTO exchange_rate VALUES ('AUD', 'HKD', 7.3276);
INSERT INTO exchange_rate VALUES ('AUD', 'GBP', 0.5512);
INSERT INTO exchange_rate VALUES ('AUD', 'CAD', 1.007);
INSERT INTO exchange_rate VALUES ('AUD', 'AUD', 1);
INSERT INTO exchange_rate VALUES ('AUD', 'CHF', 0.8384);
INSERT INTO exchange_rate VALUES ('AUD', 'SGD', 1.1777);

INSERT INTO exchange_rate VALUES ('CHF', 'CNY', 6.994);
INSERT INTO exchange_rate VALUES ('CHF', 'USD', 1.1273);
INSERT INTO exchange_rate VALUES ('CHF', 'EUR', 0.823);
INSERT INTO exchange_rate VALUES ('CHF', 'JPY', 114.4749);
INSERT INTO exchange_rate VALUES ('CHF', 'HKD', 8.7390);
INSERT INTO exchange_rate VALUES ('CHF', 'GBP', 0.6576);
INSERT INTO exchange_rate VALUES ('CHF', 'CAD', 1.2012);
INSERT INTO exchange_rate VALUES ('CHF', 'AUD', 1.1926);
INSERT INTO exchange_rate VALUES ('CHF', 'CHF', 1);
INSERT INTO exchange_rate VALUES ('CHF', 'SGD', 1.4049);

INSERT INTO admin VALUES (1, 'admin', md5('123'));
