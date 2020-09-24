<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

(function() {
	$_EXTKEY = 't2_scss';
	TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'SCSS Setup');
})();
