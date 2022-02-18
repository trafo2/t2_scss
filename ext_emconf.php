<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'SCSS for TYPO3',
	'description' => 'An easy to use extbase extension for using SCSS in TYPO3.',
	'category' => 'plugin',
	'version' => '2.0.0',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'clearcacheonload' => 0,
	'author' => 'Trafo2 GmbH',
	'author_email' => 'info@trafo2.de',
	'author_company' => 'Trafo2 GmbH',
	'constraints' => [
		'depends' => [
			'typo3' => '7.6.0-11.5.99',
			'php' => '7.1.0-8.1.99'
		],
		'conflicts' => [],
		'suggests' => [],
	],
];
