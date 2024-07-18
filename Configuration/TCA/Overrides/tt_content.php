<?php
defined('TYPO3') or die('Access denied.');

$GLOBALS['TCA']['tt_content']['palettes']['containerAppearance'] = [
    'label' => 'LLL:EXT:container_wrap/Resources/Private/Language/locallang_db.xlf:palette.containerAppearance',
    'showitem' => '',
];
$GLOBALS['TCA']['tt_content']['palettes']['containerSettings'] = [
    'label' => 'LLL:EXT:container_wrap/Resources/Private/Language/locallang_db.xlf:palette.containerSettings',
    'showitem' => 'pi_flexform',
];