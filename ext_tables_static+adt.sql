#
# Table structure for table 'tx_mnepiserver2typo3_episerver_language_translation'
#
DROP TABLE IF EXISTS tx_mnepiserver2typo3_episerver_language_translation;
CREATE TABLE tx_mnepiserver2typo3_episerver_language_translation (
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
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);

INSERT INTO tx_mnepiserver2typo3_episerver_language_translation (pid, tstamp, crdate, cruser_id, deleted, hidden, episerver_language_code, typo3_language_code) VALUES (0, UNIX_TIMESTAMP, UNIX_TIMESTAMP, 0, 0, 0, 'en', 'en');
INSERT INTO tx_mnepiserver2typo3_episerver_language_translation (pid, tstamp, crdate, cruser_id, deleted, hidden, episerver_language_code, typo3_language_code) VALUES (0, UNIX_TIMESTAMP, UNIX_TIMESTAMP, 0, 0, 0, 'sv', 'se');
INSERT INTO tx_mnepiserver2typo3_episerver_language_translation (pid, tstamp, crdate, cruser_id, deleted, hidden, episerver_language_code, typo3_language_code) VALUES (0, UNIX_TIMESTAMP, UNIX_TIMESTAMP, 0, 0, 0, 'dk', 'dk');
INSERT INTO tx_mnepiserver2typo3_episerver_language_translation (pid, tstamp, crdate, cruser_id, deleted, hidden, episerver_language_code, typo3_language_code) VALUES (0, UNIX_TIMESTAMP, UNIX_TIMESTAMP, 0, 0, 0, 'de', 'de');
INSERT INTO tx_mnepiserver2typo3_episerver_language_translation (pid, tstamp, crdate, cruser_id, deleted, hidden, episerver_language_code, typo3_language_code) VALUES (0, UNIX_TIMESTAMP, UNIX_TIMESTAMP, 0, 0, 0, 'no', 'no');