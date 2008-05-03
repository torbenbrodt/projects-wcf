-- save a copy of the password as sha-1 string
ALTER TABLE wcf1_user ADD projectPassword varchar(64) default NULL;

-- to acticate/deactive
ALTER TABLE wcf1_group ADD project tinyint(1) UNSIGNED NOT NULL default 0;

-- shortname has to be set
ALTER TABLE wcf1_group ADD projectShortName varchar(32) NOT NULL default '';

-- if this is set we will build svn/trac locally
ALTER TABLE wcf1_group ADD projectIntern tinyint(1) UNSIGNED NOT NULL default 0;

-- link to the website
ALTER TABLE wcf1_group ADD projectWebsite varchar(255) NOT NULL default '';

-- for fetching purpose
ALTER TABLE wcf1_group ADD projectSvn varchar(255) NOT NULL default '';

DROP TABLE IF EXISTS wcf1_group_to_licenses;
CREATE TABLE wcf1_group_to_licenses (
	groupID int(11) UNSIGNED NOT NULL DEFAULT 0,
	licenseID int(5) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY  (groupID,licenseID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_projectLicenses;
CREATE TABLE wcf1_projectLicenses (
	licenseID int(5) UNSIGNED NOT NULL auto_increment,
	licenseName varchar(128),
	licenseURL varchar(255),
	PRIMARY KEY  (licenseID),
	UNIQUE (licenseName)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_projectSvn;
CREATE TABLE wcf1_projectSvn (
	groupID int(11) UNSIGNED NOT NULL DEFAULT 0,
	revision int(8) UNSIGNED NOT NULL,
	userID int(11) UNSIGNED NOT NULL DEFAULT 0,
	author varchar(255) NOT NULL,
	timestamp int(12) NOT NULL,
	msg TEXT NOT NULL,
	PRIMARY KEY  (groupID,revision)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- views for mysql auth
DROP VIEW IF EXISTS wcf1_projectAuth;
CREATE VIEW wcf1_projectAuth AS SELECT DISTINCT
	username,
	IF(username='guest', SHA1('guest'), projectPassword) AS passwd 
FROM 	wcf1_user 
	NATURAL JOIN wcf1_user_to_groups 
	NATURAL JOIN wcf1_group 
WHERE 	projectIntern = 1 
AND 	NOT ISNULL(projectPassword)
UNION
	SELECT 'svncron' AS username, '3727383c1ba604a3ae57f47ee618717479cce7a6' AS passwd;

DROP VIEW IF EXISTS wcf1_projectAuthGroup;
CREATE VIEW wcf1_projectAuthGroup AS SELECT
	projectShortname AS groups,
	username 
FROM 	wcf1_group 
	NATURAL JOIN wcf1_user_to_groups 
	NATURAL JOIN wcf1_user 
WHERE 	projectIntern = 1
AND 	NOT ISNULL(projectPassword)
UNION 
	SELECT projectShortName AS groups, 'svncron' AS username FROM wcf1_group WHERE projectIntern = 1;

-- licenses
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Academic Free License','http://opensource.org/licenses/afl-3.0.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Adaptive Public License','http://opensource.org/licenses/apl1.0.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Apache Software License','http://opensource.org/licenses/apachepl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Apache License 2.0','http://opensource.org/licenses/apache2.0.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Apple Public Source License','http://opensource.org/licenses/apsl-2.0.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Artistic license','http://opensource.org/licenses/artistic-license.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Artistic license 2.0','http://opensource.org/licenses/artistic-license-2.0.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Attribution Assurance Licenses','http://opensource.org/licenses/attribution.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('New BSD license','http://opensource.org/licenses/bsd-license.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Computer Associates Trusted Open Source License 1.1','http://opensource.org/licenses/ca-tosl1.1.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Common Development and Distribution License','http://opensource.org/licenses/cddl1.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Common Public Attribution License 1.0 (CPAL)','http://opensource.org/licenses/cpal_1.0');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Common Public License 1.0','http://opensource.org/licenses/cpl1.0.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('CUA Office Public License Version 1.0','http://opensource.org/licenses/cuaoffice.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('EU DataGrid Software License','http://opensource.org/licenses/eudatagrid.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Eclipse Public License','http://opensource.org/licenses/eclipse-1.0.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Educational Community License Version 2.0','http://opensource.org/licenses/ecl2.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Eiffel Forum License','http://opensource.org/licenses/eiffel.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Eiffel Forum License V2.0','http://opensource.org/licenses/ver2_eiffel.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Entessa Public License','http://opensource.org/licenses/entessa.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Fair License','http://opensource.org/licenses/fair.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Frameworx License','http://opensource.org/licenses/frameworx.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('GNU General Public License (GPL)','http://opensource.org/licenses/gpl-license.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('GNU Library or "Lesser" General Public License (LGPL)','http://opensource.org/licenses/lgpl-license.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Historical Permission Notice and Disclaimer','http://opensource.org/licenses/historical.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('IBM Public License','http://opensource.org/licenses/ibmpl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Intel Open Source License','http://opensource.org/licenses/intel-open-source-license.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Jabber Open Source License','http://opensource.org/licenses/jabberpl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Lucent Public License (Plan9)','http://opensource.org/licenses/plan9.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Lucent Public License Version 1.02','http://opensource.org/licenses/lucent1.02.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('MIT license','http://opensource.org/licenses/mit-license.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('MITRE Collaborative Virtual Workspace License (CVW License)','http://opensource.org/licenses/mitrepl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Motosoto License','http://opensource.org/licenses/motosoto.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Mozilla Public License 1.0 (MPL)','http://opensource.org/licenses/mozilla1.0.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Mozilla Public License 1.1 (MPL)','http://opensource.org/licenses/mozilla1.1.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('NASA Open Source Agreement 1.3','http://opensource.org/licenses/nasa1.3.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Naumen Public License','http://opensource.org/licenses/naumen.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Nethack General Public License','http://opensource.org/licenses/nethack.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Nokia Open Source License','http://opensource.org/licenses/nokia.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('OCLC Research Public License 2.0','http://opensource.org/licenses/oclc2.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Open Group Test Suite License','http://opensource.org/licenses/opengroup.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Open Software License','http://opensource.org/licenses/osl-3.0.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('PHP License','http://opensource.org/licenses/php.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Python license','http://opensource.org/licenses/pythonpl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Python Software Foundation License','http://opensource.org/licenses/PythonSoftFoundation.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Qt Public License (QPL)','http://opensource.org/licenses/qtpl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('RealNetworks Public Source License V1.0','http://opensource.org/licenses/real.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Reciprocal Public License','http://opensource.org/licenses/rpl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Ricoh Source Code Public License','http://opensource.org/licenses/ricohpl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Sleepycat License','http://opensource.org/licenses/sleepycat.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Sun Industry Standards Source License (SISSL)','http://opensource.org/licenses/sisslpl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Sun Public License','http://opensource.org/licenses/sunpublic.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Sybase Open Watcom Public License 1.0','http://opensource.org/licenses/sybase.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('University of Illinois/NCSA Open Source License','http://opensource.org/licenses/UoI-NCSA.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Vovida Software License v. 1.0','http://opensource.org/licenses/vovidapl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('W3C License','http://opensource.org/licenses/W3C.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('wxWindows Library License','http://opensource.org/licenses/wxwindows.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('X.Net License','http://opensource.org/licenses/xnet.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('Zope Public License','http://opensource.org/licenses/zpl.php');
INSERT INTO wcf1_projectLicenses (licenseName, licenseURL) VALUES ('zlib/libpng license','http://opensource.org/licenses/zlib-license.php');

