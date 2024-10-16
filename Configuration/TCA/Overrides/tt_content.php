<?php

defined('TYPO3') or die('Access denied.');

$GLOBALS['TCA']['tt_content']['palettes']['containerSettings'] = [
    'label' => 'LLL:EXT:container_wrap/Resources/Private/Language/locallang_db.xlf:palette.containerSettings',
    'showitem' => '',
];
$GLOBALS['TCA']['tt_content']['palettes']['containerFlexform'] = [
    'label' => 'LLL:EXT:container_wrap/Resources/Private/Language/locallang_db.xlf:palette.containerFlexform',
    'showitem' => 'pi_flexform',
];
$GLOBALS['TCA']['tt_content']['palettes']['containerAdditionalFields'] = [
    'label' => 'LLL:EXT:container_wrap/Resources/Private/Language/locallang_db.xlf:palette.containerAdditionalFields',
    'showitem' => 'pi_flexform',
];
