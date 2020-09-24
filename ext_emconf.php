<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'SCSS for TYPO3',
	'description' => 'An easy to use extbase extension for using SCSS in TYPO3.',
	'category' => 'plugin',
	'version' => '1.0.0',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'clearcacheonload' => 0,
	'author' => 'Trafo2 GmbH',
	'author_email' => 'info@trafo2.de',
	'author_company' => 'Trafo2 GmbH',
	'constraints' => [
		'depends' => [
			'typo3' => '7.6.0-10.4.99',
			'php' => '7.1.0-7.4.99'
		],
		'conflicts' => [],
		'suggests' => [],
	],
];
