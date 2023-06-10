CREATE DATABASE IF NOT EXISTS `login` /*!40100 DEFAULT CHARACTER SET utf8 */;

create table users
(
    guid  varchar(36)  not null
        primary key,
    email varchar(128) not null,
    constraint email
        unique (email)
)
    engine = InnoDB;

create table publicauthorization
(
    id          int auto_increment
        primary key,
    web_service text not null
)
    engine = InnoDB;



create table account
(
    guid     varchar(36)  not null
        primary key,
    password varchar(255) not null,
    salt     varchar(16)  not null,
    constraint fk_guid
        foreign key (guid) references users (guid)
            on update cascade on delete cascade
)
    engine = InnoDB;

create table accountattempts
(
    guid       varchar(36)                         not null
        primary key,
    attempt_at timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    constraint fk_guid_3
        foreign key (guid) references users (guid)
)
    engine = InnoDB;

create table accountauthorization
(
    guid        varchar(36) not null
        primary key,
    web_service text        not null,
    constraint fk_guid_4
        foreign key (guid) references users (guid)
            on update cascade on delete cascade
)
    engine = InnoDB;

create table accountotp
(
    guid     varchar(36)                         not null
        primary key,
    otp      text                                not null,
    validity timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    constraint fk_guid_2
        foreign key (guid) references users (guid)
            on update cascade on delete cascade
)
    engine = InnoDB;

create table accounttmp
(
    guid     varchar(36)  not null
        primary key,
    password varchar(128) not null,
    salt     varchar(255) not null,
    constraint fk_guid_1
        foreign key (guid) references users (guid)
            on update cascade on delete cascade
)
    engine = InnoDB;

