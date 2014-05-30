CREATE TABLE funds_account (
	id					int NOT NULL auto_increment,
	stock_account		int,
	trade_password		varchar(128),
	withdraw_password	varchar(128),
	id_card_number 		varchar(128),
	customer_name 		varchar(128),
	lost_state			int,
	cancel_state		int,
	primary key(id)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE lost_application (
	funds_account 		int NOT NULL,
	disapproved_reply 	text,
	time 				date
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE cancel_application (
	funds_account 		int NOT NULL,
	disapproved_reply 	text,
	time 				date
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE currency (
	funds_account 	int NOT NULL,
	currency_type	varchar(32),
	balance 		double,
	frozen_balance 	double
)ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE record (
	stock_account_number 	varchar(128),
	funds_account_number 	varchar(128),
	currency_type 		 	varchar(32),
	balance_before		 	double,
	balance_after		 	double,
	issave					boolean,
	issuccessful			boolean,
	time					timestamp
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE exchange_rate (
	currency_a	varchar(128),
	currency_b	varchar(128),
	rate 		double
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;