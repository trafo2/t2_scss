<?php
namespace Trafo2\T2Scss\Controller;

use Trafo2\T2Scss\Utility\Utilities;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class BaseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	const EXTENSION_NAME = 'T2Scss';

	/**
	 * @var array $configuration
	 */
	protected $configuration;

	/**
	 * @var string
	 */
	protected $inputFolder;

	/**
	 * @var string
	 */
	protected $outputFolder;

	public function __construct() {
		/* @var ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		/* @var ConfigurationManagerInterface $configurationManager */
		$configurationManager = $objectManager->get(ConfigurationManagerInterface::class);

		$this->configuration = $configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, self::EXTENSION_NAME, ''
		);

		if (!empty($this->configuration['files']['inputFolder'])) {
			$this->inputFolder = Utilities::getPath($this->configuration['files']['inputFolder'] ?? '');
		}
		if (!empty($this->configuration['files']['outputFolder'])) {
			$this->outputFolder = Utilities::getPath($this->configuration['files']['outputFolder'] ?? '');
		}
	}

	public function baseAction(): void {
		if (TYPO3_MODE != 'FE') {
			return;
		}

		if ($this->configuration['settings']['activateCompiler']) {
			if ($this->inputFolder && $this->outputFolder) {
				$files = GeneralUtility::getFilesInDir($this->inputFolder, $this->configuration['files']['extensions'], true);
				if (!empty($files)) {
					$controller = GeneralUtility::makeInstance(PhpCompilerController::class);
					$controller->runCompiler($files);
				} else {
					echo Utilities::wrapErrorMessage(LocalizationUtility::translate('emptyInputFolder', self::EXTENSION_NAME, [$this->inputFolder]));
				}
			} else {
				echo Utilities::wrapErrorMessage(LocalizationUtility::translate('missingFolders', self::EXTENSION_NAME));
			}
		}
	}
}
