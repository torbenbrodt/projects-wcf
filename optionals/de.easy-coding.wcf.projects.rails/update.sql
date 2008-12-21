-- views for ftp auth
DROP VIEW wcf1_projectRailsAuth;
CREATE VIEW wcf1_projectRailsAuth AS 
	SELECT 
			CONCAT(REPLACE(username,'@',''),'@',projectShortName) AS User,
			1 AS status,
			projectPassword2 AS Password,
			projectShortName AS Uid,
			projectShortName AS Gid,
			CONCAT('/home/',projectShortName) AS Dir,
			100 AS ULBandwidth,
			100 AS DLBandwidth,
			'' AS comment,
			'*' AS ipaccess,
			250 AS QuotaSize,
			0 AS QuotaFiles
	FROM wcf1_group 
	INNER JOIN wcf1_user_to_groups USING(groupID)
	INNER JOIN wcf1_user USING(userID)
	WHERE wcf1_group.projectRails = 1;
