CREATE TABLE FundsAccount(
	id 	int(11) NOT NULL auto_increment,
	stock_account_number 	 varchar(128),
	funds_account_number 	 varchar(128),
	password_for_trading 	 varchar(128),
	password_for_withdrawing varchar(128),
	primary key(id)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE PersonInfo (
	funds_account 	int(11) NOT NULL,
	IDNumber 		varchar(128),
	customer_name 	varchar(128),
	tel_number 		varchar(128)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE AccountState (
	funds_account 	int(11) NOT NULL,
	lost_reported 	boolean,
	lost_handled 	boolean,
	lost_approved	boolean,
	lost_info		text,
	close_reported	boolean,
	close_handled	boolean,
	close_approved	boolean,
	close_info		text
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;


CREATE TABLE Currency (
	funds_account 	int(11) NOT NULL,
	currency_type	varchar(32),
	balance 		double,
	frozen_balance 	double
)ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE Record (
	stock_account_number 	varchar(128),
	funds_account_number 	varchar(128),
	currency_type 		 	varchar(32),
	balance_before		 	double,
	balance_after		 	double,
	issave					boolean,
	issuccessful			boolean,
	time					timestamp
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;