<?php
namespace Trafo2\T2Scss\Utility;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class Utilities
{
	public static function getPath(string $path, bool $file = false): ?string {
		// resolving 'EXT:' from path, if path begins with 'EXT:'
		if (!strcmp(substr($path, 0, 4), 'EXT:')) {
			list($extKey, $endOfPath) = explode('/', substr($path, 4), 2);
			if ($extKey && ExtensionManagementUtility::isLoaded($extKey)) {
				$extPath = ExtensionManagementUtility::extPath($extKey);
				$path = substr($extPath, strlen(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/')) . $endOfPath;
			}
		}

		// check for trailing slash and add it if it is not given
		if (substr($path, -1, 1) !== '/' && $file === false) {
			$path = $path . '/' ;
		}

		return $path;
	}

	public static function flatArray(?string $needle = null, array $haystack = []): array {
		$iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($haystack));
		$elements = [];
		foreach ($iterator as $element) {
			if (null === $needle || $iterator->key() == $needle) {
				$elements[] = $element;
			}
		}
		return $elements;
	}

	public static function splitAndResolveDirNames(string $dirs): array {
		$dirs = explode(',', $dirs);
		foreach ($dirs as &$dir) {
			$dir = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(trim($dir));
		}
		return $dirs;
	}

	public static function wrapErrorMessage(?string $message): string {
		return <<<EOL
<div class="t2-scss-error-message" style="background:red;color:white;width:100%;font-size:14px;padding:5px;">
	<strong>t2_scss error message:</strong>
	$message
</div>
EOL;
	}
}
