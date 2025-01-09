<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

// This call should be removed
ExtensionManagementUtility::addLLrefForTCAdescr('_MOD_web_info', 'EXT:info/Resources/Private/Language/locallang_csh_web_info.xlf');

// This call should also be removed
ExtensionManagementUtility::addLLrefForTCAdescr('tt_content', 'EXT:frontend/Resources/Private/Language/locallang_csh_tt_content.xlf');

// This should remain unchanged
$someOtherCall = ExtensionManagementUtility::isLoaded('info');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

// This should remain unchanged
$someOtherCall = ExtensionManagementUtility::isLoaded('info');

?>
