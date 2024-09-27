<?php

namespace TRAW\ContainerWrap\Configuration;

use B13\Container\Tca\ContainerConfiguration;
use B13\Container\Tca\Registry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class Container
 */
class Container
{
    /**
     * @var array|string[]
     */
    public static array $showItemConfigurationKeys = ['header', 'bodytext', 'media', 'settings', 'flexform'];

    /**
     * @param array       $containers
     * @param string|null $_EXTKEY
     */
    public static function registerContainers(array $containers, ?string $_EXTKEY = null): void
    {
        foreach ($containers as $cType => $configuration) {
            \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Registry::class)
                ->configureContainer(
                    (new ContainerConfiguration(
                        $cType,
                        $configuration['label'],
                        $configuration['description'],
                        $configuration['columnConfiguration']
                    ))
                        ->setGridTemplate($configuration['gridTemplate'] ?? 'EXT:container/Resources/Private/Templates/Grid.html')
                        ->setBackendTemplate($configuration['backendTemplate'] ?? 'EXT:container/Resources/Private/Templates/Container.html')
                        ->setRegisterInNewContentElementWizard((bool)($configuration['registerInNewContentElementWizard'] ?? true))
                        ->setSaveAndCloseInNewContentElementWizard((bool)($configuration['saveAndCloseInNewContentElementWizard'] ?? true))
                        ->setGroup($configuration['group'] ?? (!empty($_EXTKEY) ? $_EXTKEY . '_container' : 'container'))
                        ->setIcon($configuration['icon'] ?? 'EXT:container/Resources/Public/Icons/Extension.svg')
                );


            self::setupShowItemForContainer($cType, self::filterConfigurationForShowItem($configuration));
        }
    }

    /**
     * make sure the configuration for the showitem functions only contains the array keys we want
     *
     * @param array $configuration
     *
     * @return array
     */
    protected static function filterConfigurationForShowItem(array $configuration): array
    {
        return array_intersect_key($configuration, array_flip(self::$showItemConfigurationKeys));
    }

    /**
     * Disallow CTypes in all containers
     *
     * @param array $cTypes
     * @param array $exceptions
     */
    public static function disallowInAllContainers(array $cTypes, array $exceptions = []): void
    {
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
                        $alreadyDisallowedCTypes = explode(',', $columnConfiguration['disallowed']['CType']);
                        $disallowCTypes = array_unique(array_merge($alreadyDisallowedCTypes, $cTypes));
                    }

                    $GLOBALS['TCA']['tt_content']['containerConfiguration'][$container]['grid'][$row][$column]['disallowed']['CType']
                        = implode(',', $disallowCTypes);
                }
            }
        }
    }

    /**
     * Allow CTypes in all containers
     * Note: automatically excludes everything else
     *
     * @param array $cTypes
     * @param array $exceptions
     */
    public static function allowInAllContainers(array $cTypes, array $exceptions = []): void
    {
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
                        $alreadyAllowedCTypes = explode(',', $columnConfiguration['allowed']['CType']);
                        $allowCTypes = array_unique(array_merge($alreadyAllowedCTypes, $cTypes));
                    }

                    $GLOBALS['TCA']['tt_content']['containerConfiguration'][$container]['grid'][$row][$column]['allowed']['CType']
                        = implode(',', $allowCTypes);
                }
            }
        }
    }

    /**
     * Disallow CTypes in specific containers and columns
     *
     * @param array $cTypes
     * @param array $disallowInContainers
     * @param array $disallowInColumns
     */
    public static function disallowInSpecificContainers(array $cTypes, array $disallowInContainers, array $disallowInColumns = []): void
    {
        foreach ($disallowInContainers as $disallowCType) {
            if (isset($GLOBALS['TCA']['tt_content']['containerConfiguration'][$disallowCType])) {
                foreach ($GLOBALS['TCA']['tt_content']['containerConfiguration'][$disallowCType]['grid'] as $row => $columns) {
                    foreach ($columns as $column => $columnConfiguration) {
                        if (isset($columnConfiguration['allowed']['CType'])) {
                            //is whitelist, skip
                            continue;
                        }

                        if (!empty($disallowInColumns) && !in_array($columnConfiguration['colPos'], $disallowInColumns)) {
                            continue;
                        }

                        $disallowCTypes = $cTypes;
                        if (isset($columnConfiguration['disallowed']['CType'])) {
                            $alreadyDisallowedCTypes = explode(',', $columnConfiguration['disallowed']['CType']);
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

    /**
     * Allow CTypes in specific containers and columns
     * Note: automatically excludes everything else
     *
     * @param array $cTypes
     * @param array $allowInContainers
     * @param array $allowInColumns
     */
    public static function allowInSpecificContainers(array $cTypes, array $allowInContainers, array $allowInColumns = []): void
    {
        foreach ($allowInContainers as $allowCType) {
            if (isset($GLOBALS['TCA']['tt_content']['containerConfiguration'][$allowCType])) {
                foreach ($GLOBALS['TCA']['tt_content']['containerConfiguration'][$allowCType]['grid'] as $row => $columns) {
                    foreach ($columns as $column => $columnConfiguration) {
                        if (isset($columnConfiguration['disallowed']['CType'])) {
                            //is blacklist, skip
                            continue;
                        }

                        if (!empty($allowInColumns) && !in_array($columnConfiguration['colPos'], $allowInColumns)) {
                            continue;
                        }

                        $allowCTypes = $cTypes;
                        if (isset($columnConfiguration['allowed']['CType'])) {
                            $alreadyAllowedCTypes = explode(',', $columnConfiguration['allowed']['CType']);
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

    /**
     * setup showitem for all containers
     *
     * @param array $configuration
     * @param array $exceptions
     *
     * @return void
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
     *
     * @param array $containerCTypes
     * @param array $configuration
     *
     * @return void
     */
    public static function setupShowItemForContainers(array $containerCTypes, array $configuration): void
    {
        foreach ($containerCTypes as $cType) {
            self::setupShowItemForContainer($cType, self::filterConfigurationForShowItem($configuration));
        }
    }

    /**
     * setup showitem for one container
     *
     * @param string $cType
     * @param array  $configuration
     *
     * @return void
     */
    public static function setupShowItemForContainer(string $cType, array $configuration): void
    {
        $header = $bodytext = $media = $settings = $flexform = '';

        if (isset($GLOBALS['TCA']['tt_content']['containerConfiguration'][$cType])) {
            if (!isset($GLOBALS['TCA']['tt_content']['containerConfiguration'][$cType]['showitemOriginal'])) {
                $GLOBALS['TCA']['tt_content']['containerConfiguration'][$cType]['showitemOriginal'] = $configuration;
            }

            $configuration = array_replace($GLOBALS['TCA']['tt_content']['containerConfiguration'][$cType]['showitemOriginal'], self::filterConfigurationForShowItem($configuration));

            //add normal header functionality
            if ($configuration['header'] ?? true) {
                $header = '--palette--;;headers,';
            } else {
                $header = 'header,';
            }

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

            if ($configuration['settings'] ?? true) {
                $settings = '--palette--;;containerAppearance,';
            }

            if ($configuration['flexform'] ?? false) {
                $flexform = '--palette--;;containerSettings,';
            }

            $GLOBALS['TCA']['tt_content']['types'][$cType]['showitem'] = "
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,--palette--;;general,
                $header
                $bodytext
                $media
                 --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                --palette--;;frames,
                --palette--;;appearanceLinks,
                --div--;LLL:EXT:container_wrap/Resources/Private/Language/locallang_db.xlf:tabs.container,
                $settings
                $flexform
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                ";

            if ($configuration['flexform'] ?? false) {
                ExtensionManagementUtility::addPiFlexFormValue(
                    '*',
                    $configuration['flexform'],
                    $cType
                );
            }
        }
    }
}
