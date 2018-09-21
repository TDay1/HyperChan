CREATE SCHEMA hyperchan; 

CREATE TABLE `hyperchan`.`posts` (
     postid int NOT NULL AUTO_INCREMENT,
     post varchar(3000),
     dateid varchar(20),
     imageenabled bit,
     imageext varchar(4),
     PRIMARY KEY (postid) );

CREATE TABLE `hyperchan`.`comments` (
     commentid int NOT NULL AUTO_INCREMENT,
     postid int,
     comment varchar(3000),
     dateid varchar(20),
     imageenabled bit,
     imageext varchar(4),
     PRIMARY KEY (commentid) );