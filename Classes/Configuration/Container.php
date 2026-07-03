<?php

declare(strict_types=1);

namespace TRAW\ContainerWrap\Configuration;

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Container
 */
class Container
{
    /**
     * @var array|string[]
     */
    public static array $showItemConfigurationKeys = ['header', 'bodytext', 'media', 'settings', 'flexform', 'additionalFields', 'columnsOverrides', 'appearance', 'categories'];

    public static function registerContainers(array $containers, ?string $_EXTKEY = null): void
    {
        foreach ($containers as $cType => $configuration) {
            $containerConfiguration = new ContainerConfiguration(
                $configuration['value'] ?? $cType,
                $configuration['label'] ?? $cType,
                $configuration['description'] ?? '',
                $configuration['columnConfiguration'] ?? []
            );
            $containerConfiguration->setRegisterInNewContentElementWizard((bool)($configuration['registerInNewContentElementWizard'] ?? true))
                ->setSaveAndCloseInNewContentElementWizard((bool)($configuration['saveAndCloseInNewContentElementWizard'] ?? true))
                ->setGroup($configuration['group'] ?? (in_array($_EXTKEY, [null, '', '0'], true) ? 'container' : $_EXTKEY . '_container'))
                ->setIcon($configuration['icon'] ?? 'EXT:container/Resources/Public/Icons/Extension.svg');
            if (!empty($configuration['backendTemplate'])) {
                $containerConfiguration->setBackendTemplate($configuration['backendTemplate']);
            }

            if (!empty($configuration['gridTemplate'])) {
                $containerConfiguration->setGridTemplate($configuration['gridTemplate']);
            }

            if (!empty($configuration['gridLayoutPaths'])) {
                if (is_array($configuration['gridLayoutPaths'])) {
                    $containerConfiguration->setGridLayoutPaths(array_unique(array_values($configuration['gridLayoutPaths'])));
                } elseif (is_string($configuration['gridLayoutPaths'])) {
                    if (!in_array($configuration['gridLayoutPaths'], $containerConfiguration->getGridLayoutPaths(), true)) {
                        $containerConfiguration->addGridLayoutPath($configuration['gridLayoutPaths']);
                    }
                }
            }

            if (!empty($configuration['gridPartialPaths'])) {
                if (is_array($configuration['gridPartialPaths'])) {
                    $containerConfiguration->setGridPartialPaths(array_unique(array_values($configuration['gridPartialPaths'])));
                } elseif (is_string($configuration['gridPartialPaths'])) {
                    if (!in_array($configuration['gridPartialPaths'], $containerConfiguration->getGridPartialPaths(), true)) {
                        $containerConfiguration->addGridPartialPath($configuration['gridPartialPaths']);
                    }
                }
            }

            if (isset($configuration['relativeToField']) && $configuration['relativeToField'] !== '') {
                $containerConfiguration->setRelativeToField((string)$configuration['relativeToField']);
            }

            if (isset($configuration['relativePosition']) && $configuration['relativePosition'] !== '') {
                $containerConfiguration->setRelativePosition((string)$configuration['relativePosition']);
            }

            if (!empty($configuration['defaultValues']) && is_array($configuration['defaultValues'])) {
                $containerConfiguration->setDefaultValues($configuration['defaultValues']);
            }

            \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Registry::class)
                ->configureContainer($containerConfiguration);

            self::setupShowItemForContainer($cType, self::filterConfigurationForShowItem($configuration));
        }
    }

    /**
     * make sure the configuration for the showitem functions only contains the array keys we want
     *
     *
     */
    protected static function filterConfigurationForShowItem(array $configuration): array
    {
        return array_intersect_key($configuration, array_flip(self::$showItemConfigurationKeys));
    }

    /**
     * Disallow CTypes in all containers
     */
    public static function disallowInAllContainers(array $cTypes, array $exceptions = []): void
    {
        if (ExtensionManagementUtility::isLoaded('content_defender')) {
            foreach ($GLOBALS['TCA']['tt_content']['containerConfiguration'] as $container => $configuration) {
                if (in_array($container, $exceptions)) {
                    continue;
                }

                foreach ($configuration['grid'] as $row => $columns) {
                    foreach ($columns as $column => $columnConfiguration) {
                        if (isset($configuration['grid'][$row][$column]['allowed']['CType'])) {
                            //is whitelist, skip
                            continue;
                        }

                        $disallowCTypes = $cTypes;
                        if (isset($columnConfiguration['disallowed']['CType'])) {
                            $alreadyDisallowedCTypes = GeneralUtility::trimExplode(',', (string)$columnConfiguration['disallowed']['CType'], true);
                            $disallowCTypes = array_unique(array_merge($alreadyDisallowedCTypes, $cTypes));
                        }

                        $GLOBALS['TCA']['tt_content']['containerConfiguration'][$container]['grid'][$row][$column]['disallowed']['CType']
                            = implode(',', $disallowCTypes);
                    }
                }
            }
        }
    }

    /**
     * Allow CTypes in all containers
     * Note: automatically excludes everything else
     */
    public static function allowInAllContainers(array $cTypes, array $exceptions = []): void
    {
        if (ExtensionManagementUtility::isLoaded('content_defender')) {
            foreach ($GLOBALS['TCA']['tt_content']['containerConfiguration'] as $container => $configuration) {
                if (in_array($container, $exceptions)) {
                    continue;
                }

                foreach ($configuration['grid'] as $row => $columns) {
                    foreach ($columns as $column => $columnConfiguration) {
                        if (isset($configuration['grid'][$row][$column]['disallowed']['CType'])) {
                            //is blacklist, skip
                            continue;
                        }

                        $allowCTypes = $cTypes;
                        if (isset($columnConfiguration['allowed']['CType'])) {
                            $alreadyAllowedCTypes = GeneralUtility::trimExplode(',', (string)$columnConfiguration['allowed']['CType'], true);
                            $allowCTypes = array_unique(array_merge($alreadyAllowedCTypes, $cTypes));
                        }

                        $GLOBALS['TCA']['tt_content']['containerConfiguration'][$container]['grid'][$row][$column]['allowed']['CType']
                            = implode(',', $allowCTypes);
                    }
                }
            }
        }
    }

    /**
     * Disallow CTypes in specific containers and columns
     */
    public static function disallowInSpecificContainers(array $cTypes, array $disallowInContainers, array $disallowInColumns = []): void
    {
        if (ExtensionManagementUtility::isLoaded('content_defender')) {
            foreach ($disallowInContainers as $disallowCType) {
                if (isset($GLOBALS['TCA']['tt_content']['containerConfiguration'][$disallowCType])) {
                    foreach ($GLOBALS['TCA']['tt_content']['containerConfiguration'][$disallowCType]['grid'] as $row => $columns) {
                        foreach ($columns as $column => $columnConfiguration) {
                            if (isset($columnConfiguration['allowed']['CType'])) {
                                //is whitelist, skip
                                continue;
                            }

                            if ($disallowInColumns !== [] && !in_array($columnConfiguration['colPos'], $disallowInColumns)) {
                                continue;
                            }

                            $disallowCTypes = $cTypes;
                            if (isset($columnConfiguration['disallowed']['CType'])) {
                                $alreadyDisallowedCTypes = GeneralUtility::trimExplode(',', (string)$columnConfiguration['disallowed']['CType'], true);
                                $disallowCTypes = array_unique(array_merge($alreadyDisallowedCTypes, $cTypes));
                            }

                            $GLOBALS['TCA']['tt_content']['containerConfiguration'][$disallowCType]['grid'][$row][$column]['disallowed']['CType']
                                = implode(',', $disallowCTypes);

                            unset($alreadyDisallowedCTypes);
                        }
                    }
                }
            }
        }
    }

    /**
     * Allow CTypes in specific containers and columns
     * Note: automatically excludes everything else
     */
    public static function allowInSpecificContainers(array $cTypes, array $allowInContainers, array $allowInColumns = []): void
    {
        if (ExtensionManagementUtility::isLoaded('content_defender')) {
            foreach ($allowInContainers as $allowCType) {
                if (isset($GLOBALS['TCA']['tt_content']['containerConfiguration'][$allowCType])) {
                    foreach ($GLOBALS['TCA']['tt_content']['containerConfiguration'][$allowCType]['grid'] as $row => $columns) {
                        foreach ($columns as $column => $columnConfiguration) {
                            if (isset($columnConfiguration['disallowed']['CType'])) {
                                //is blacklist, skip
                                continue;
                            }

                            if ($allowInColumns !== [] && !in_array($columnConfiguration['colPos'], $allowInColumns)) {
                                continue;
                            }

                            $allowCTypes = $cTypes;
                            if (isset($columnConfiguration['allowed']['CType'])) {
                                $alreadyAllowedCTypes = GeneralUtility::trimExplode(',', (string)$columnConfiguration['allowed']['CType'], true);
                                $allowCTypes = array_unique(array_merge($alreadyAllowedCTypes, $cTypes));
                            }

                            $GLOBALS['TCA']['tt_content']['containerConfiguration'][$allowCType]['grid'][$row][$column]['allowed']['CType']
                                = implode(',', $allowCTypes);

                            unset($alreadyAllowedCTypes);
                        }
                    }
                }
            }
        }
    }

    /**
     * setup showitem for all containers
     */
    public static function setupShowItemForAllContainers(array $configuration, array $exceptions = []): void
    {
        foreach (array_keys($GLOBALS['TCA']['tt_content']['containerConfiguration']) as $cType) {
            if (in_array($cType, $exceptions)) {
                continue;
            }

            self::setupShowItemForContainer($cType, self::filterConfigurationForShowItem($configuration));
        }
    }

    /**
     * setup show item for some containers
     */
    public static function setupShowItemForContainers(array $containerCTypes, array $configuration): void
    {
        foreach ($containerCTypes as $cType) {
            self::setupShowItemForContainer($cType, self::filterConfigurationForShowItem($configuration));
        }
    }

    /**
     * setup showitem for one container
     */
    public static function setupShowItemForContainer(string $cType, array $configuration): void
    {
        $bodytext = '';
        $media = '';
        $settings = '';
        $flexform = '';
        $additionalFields = '';
        $frames = '';
        $appearanceLinks = '';
        $categories = '';
        if (isset($GLOBALS['TCA']['tt_content']['containerConfiguration'][$cType])) {
            if (!isset($GLOBALS['TCA']['tt_content']['containerConfiguration'][$cType]['showitemOriginal'])) {
                $GLOBALS['TCA']['tt_content']['containerConfiguration'][$cType]['showitemOriginal'] = $configuration;
            }

            $configuration = array_replace($GLOBALS['TCA']['tt_content']['containerConfiguration'][$cType]['showitemOriginal'], self::filterConfigurationForShowItem($configuration));

            //add normal header functionality
            $header = $configuration['header'] ?? false ? '--palette--;;headers,' : 'header,';

            //add bodytext
            if ($configuration['bodytext'] ?? false) {
                $bodytext = 'bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel,';
                $GLOBALS['TCA']['tt_content']['types'][$cType]['columnsOverrides']['bodytext']['config'] = [
                    'rows' => 5,
                    'enableRichtext' => true,
                ];
            }

            if ($configuration['media'] ?? false) {
                $media = '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media,
                            assets,';
            }

            if ($configuration['settings'] ?? false) {
                $settings = '--palette--;;containerSettings,';
            }

            if ($configuration['flexform'] ?? false) {
                $flexform = '--palette--;;containerFlexform,';
            }

            if ($configuration['additionalFields'] ?? false) {
                $additionalFields = '--palette--;;containerAdditionalFields,';
            }

            if ($configuration['appearance']['frames'] ?? true) {
                $frames = '--palette--;;frames,';
            }

            if ($configuration['appearance']['appearanceLinks'] ?? true) {
                $appearanceLinks = ' --palette--;;appearanceLinks,';
            }

            if ($configuration['categories'] ?? true) {
                $categories = 'categories,';
            }

            $GLOBALS['TCA']['tt_content']['types'][$cType]['showitem'] = "
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,--palette--;;general,
                {$header}
                {$bodytext}
                {$media}
                 --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                {$frames},
                {$appearanceLinks},
                --div--;LLL:EXT:container_wrap/Resources/Private/Language/locallang_db.xlf:tabs.container,
                {$settings}
                {$flexform}
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    {$categories}
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                {$additionalFields}
                ";

            if ($configuration['flexform'] ?? false) {
                if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() < 14) {
                    // @extensionScannerIgnoreLine
                    ExtensionManagementUtility::addPiFlexFormValue(
                        '*',
                        $configuration['flexform'],
                        $cType
                    );
                } else {
                    $GLOBALS['TCA']['tt_content']['types'][$cType]['columnsOverrides']['pi_flexform']['config']['ds']
                        = $configuration['flexform'];
                }

            }

            if ($configuration['columnsOverrides'] ?? false) {
                $GLOBALS['TCA']['tt_content']['types'][$cType]['columnsOverrides'] = array_replace_recursive(
                    $GLOBALS['TCA']['tt_content']['types'][$cType]['columnsOverrides'] ?? [],
                    $configuration['columnsOverrides']
                );
            }
        }
    }
}
