#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_mnepiserver2typo3_episerver_id int(11) DEFAULT '0' NOT NULL,
    tx_mnepiserver2typo3_episerver_site_id int(11) DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_mnepiserver2typo3_episerver_id int(11) DEFAULT '0' NOT NULL,
    tx_mnepiserver2typo3_episerver_site_id int(11) DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_mnepiserver2typo3_episerver'
#
CREATE TABLE tx_mnepiserver2typo3_episerver (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumtext,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	domain tinytext,
	ws_username tinytext,
	ws_password tinytext,
    episerver_startpage_id int(11) DEFAULT '0' NOT NULL,
    t3_root_page_id int(11) DEFAULT '0' NOT NULL,
    episerver_content_fields tinytext,
    episerver_languages tinytext,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_mnepiserver2typo3_episerver_language_mm'
#
CREATE TABLE tx_mnepiserver2typo3_episerver_language_mm (
    uid_local int(11) DEFAULT '0' NOT NULL,				
	uid_foreign int(11) DEFAULT '0' NOT NULL,
    tablenames tinytext,
    sorting  int(11) DEFAULT '0' NOT NULL,
    sorting_foreign  int(11) DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_mnepiserver2typo3_episerver_language_translation'
#
CREATE TABLE tx_mnepiserver2typo3_episerver_installation_languages (
    uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumtext,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
    episerver_language_code tinytext,
    typo3_language_code tinytext,
    installation_uid int(11) DEFAULT '0' NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);