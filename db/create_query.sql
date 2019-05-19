create table ibb_users (
	id_ibbUsers int primary key not null unique auto_increment,
    firstName_ibbUsers varchar(255) not null,
    lastName_ibbUsers varchar(255) not null,
    email_ibbUsers varchar(100) not null,
    account_ibbUsers varchar(10) not null unique,
    password_ibbUsers varchar(60) not null,
    balance_ibbUsers decimal(10,2) not null default 0.00
);

create table ibb_transacts (
	id_ibbTransacts int primary key not null unique auto_increment,
    action_ibbTransacts varchar(255) not null,
    date_ibbTransacts timestamp not null default current_timestamp,
    value_ibbTransacts decimal(10,2) not null,
    ip_ibbTransacts varchar(100) not null,
    userId_ibbTransacts int,
    foreign key (userId_ibbTransacts) references ibb_users(id_ibbUsers)
);

select * from ibb_users;

select ibb_users.id_ibbUsers, ibb_transacts.action_ibbTransacts, ibb_transacts.date_ibbTransacts, ibb_transacts.value_ibbTransacts, ibb_transacts.ip_ibbTransacts
from ibb_transacts 
inner join ibb_users on ibb_transacts.userId_ibbTransacts = 1 order by ibb_transacts.date_ibbTransacts desc;

ALTER TABLE ibb_users
ADD COLUMN role_ibbUsers varchar(255) not null default 'user';

select * from ibb_transacts;