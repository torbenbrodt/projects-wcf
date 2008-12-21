-- better performance
ALTER TABLE wcf1_user CHANGE projectPassword projectPassword char(40) default NULL;

-- new md5 hash
ALTER TABLE wcf1_user ADD projectPassword2 char(32) default NULL;
