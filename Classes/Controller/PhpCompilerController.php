<?php
namespace Trafo2\T2Scss\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Core\Environment;
use ScssPhp\ScssPhp as ScssPhp;

class PhpCompilerController extends BaseController
{
	public function runCompiler(array $files): void {
		if (!is_dir($this->outputFolder)) {
			$basePath = defined('PATH_site') ? PATH_site : Environment::getPublicPath();
			GeneralUtility::mkdir_deep($basePath . DIRECTORY_SEPARATOR . $this->outputFolder);
		}

		if (!defined('TYPO3_COMPOSER_MODE') || !TYPO3_COMPOSER_MODE) {
			$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t2_scss');
			require_once $extensionPath . '/Resources/Private/PHP/vendor/autoload.php';
		}

		$compiler = new ScssPhp\Compiler();
		$compiler->addImportPath($this->inputFolder);
		if ($this->configuration['settings']['compressed']) {
			$compiler->setFormatter(ScssPhp\Formatter\Compressed::class);
		}

		foreach($files as $file) {
			$pathStructure = explode('/', $file);
			$filename = array_pop($pathStructure);

			$md5 = md5($filename . md5_file($file));

			$outputFile = $this->outputFolder . substr($filename, 0, -5) . '_' . $md5 . '.css';

			if ($this->configuration['settings']['forceMode']) {
				unlink($outputFile);
			}

			if (!file_exists($outputFile)) {
				$code = file_get_contents($file);
				$parsed = $compiler->compile($code, $file);
				file_put_contents($outputFile, $parsed);
				GeneralUtility::fixPermissions($outputFile, false);
			}
		}

		// unlink compiled files which have no equal source file
		if ($this->configuration['settings']['unlinkCssFilesWithNoSourceFile']) {
			$this->unlinkGeneratedFilesWithNoSourceFile($files);
		}

		$files = GeneralUtility::getFilesInDir($this->outputFolder, 'css');
		usort($files, [$this, 'getSortOrderPhp']);

		foreach ($files as $cssFile) {
			$cssFileWithoutExt = substr($cssFile, 0, -37);
			$fileOptions = $this->configuration['settings']['include'][$cssFileWithoutExt];
			$defaultOptions = $this->configuration['settings']['include']['default'];
			$excludeFromPageRender = $fileOptions['exclude'] ?? false;
			if (!$excludeFromPageRender) {
				$pageRenderer = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
				$pageRenderer->addCssFile(
					$this->outputFolder . $cssFile,
					'stylesheet',
					$fileOptions['media'] ?? $defaultOptions['media'],
					$fileOptions['title'] ?? $defaultOptions['title'],
					$fileOptions['compress'] ?? (bool) $defaultOptions['compress'],
					$fileOptions['forceOnTop'] ?? false,
					$fileOptions['allWrap'] ?? $defaultOptions['allWrap'],
					$fileOptions['excludeFromConcatenation'] ?? (bool) $defaultOptions['excludeFromConcatenation']
				);
			}
		}
	}

	protected function unlinkGeneratedFilesWithNoSourceFile(array $sourceFiles): void {
		$srcArr = [];
		foreach ($sourceFiles as $file) {
			$pathStructure = explode('/', $file);
			$filename = array_pop($pathStructure);

			$md5 = md5($filename . md5_file($file));

			$srcArr[] = $md5;
		}

		// unlink every css file, which have no equal scss file
		// checked by comparing md5-string from filename with md5_file(source)
		foreach(GeneralUtility::getFilesInDir($this->outputFolder, 'css') as $cssFile) {
			$md5str = substr(substr($cssFile, 0, -4), -32);
			if (!in_array($md5str, $srcArr)) {
				unlink($this->outputFolder . $cssFile);
			}
		}
	}

	protected function getSortOrderPhp(string $file1, string $file2): int {
		$includeSettings = $this->configuration['settings']['include'];
		$fileOptions1 = $includeSettings[substr($file1, 0, -37)];
		$fileOptions2 = $includeSettings[substr($file2, 0, -37)];
		$sortOrder1 = $fileOptions1['sortOrder'] ?? 0;
		$sortOrder2 = $fileOptions2['sortOrder'] ?? 0;
		$forceOnTop1 = $fileOptions1['forceOnTop'] ?? false;
		$forceOnTop2 = $fileOptions2['forceOnTop'] ?? false;
		$sortDirection = 1;
		if ($forceOnTop1 || $forceOnTop2) {
			$sortDirection = -1;
		}

		if ($sortOrder1 == $sortOrder2) {
			return 0;
		}
		return $sortDirection * (($sortOrder1 < $sortOrder2) ? -1 : 1);
	}
}
