 ALTER TABLE `users` ADD UNIQUE(`user_mail`);

ALTER TABLE `users` 
	ADD `user_code` VARCHAR(255) NULL AFTER `user_pwd`, 
	ADD `user_code_date` DATETIME NULL AFTER `user_code`;