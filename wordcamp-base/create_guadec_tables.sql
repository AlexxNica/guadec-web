create table wp_guadec_registration_codes (
        code char(32) not null primary key,
        entity varchar(256) not null
)
character set utf8;

create table wp_guadec_registration (
        ID bigint(20) unsigned not null primary key auto_increment,
        user_ID bigint(20) unsigned not null unique key,
        foreign key (user_ID) references wp_users (ID),

        registration_type enum('professional', 'hobbyist', 'student', 'code') not null default 'hobbyist',
        registration_code char(32) default null,

        tshirt boolean not null default false,
        tshirt_gender enum('male', 'female') default null,
        tshirt_size enum('s', 'm', 'l', 'xl', 'xxl') default null,
        foundation boolean default null,

        lunch boolean not null default false,
        vegetarian boolean default null,

        dormitory boolean not null default false,
        breakfast boolean default null,
        check_in_date date default null,
        check_out_date date default null,
        gender enum('male', 'female') default null,
        room enum('single', 'double') default null,
        roommate varchar(256) default null,

        completed boolean not null default false,
        total_payed decimal(9) default null,

        payment_session_id char(64) default null,

        notes varchar(2048) default null,

        tax_doc_number decimal(9) default null,
        tax_document varchar(2048) default null
)
character set utf8;
