-- if this is set we will build trac locally
ALTER TABLE wcf1_group ADD projectTrac tinyint(1) UNSIGNED NOT NULL default 0;
