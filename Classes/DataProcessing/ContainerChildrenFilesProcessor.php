<?php
declare(strict_types=1);

namespace TRAW\ContainerWrap\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class ContainerChildrenFilesProcessor
 *
 * Fetch all files from all child elements into a single array
 *
 * Usage example:
 *
 * dataProcessing {
 *      100 = B13\Container\DataProcessing\ContainerProcessor
 *      100 {
 *          colPos = 100
 *          as = my_children_array_variable
 *
 *          dataProcessing {
 *              10 = files
 *              10 {
 *                  references.fieldName = assets
 *                  as = my_children_files
 *              }
 *          }
 *      }
 *
 *      110 = traw-container-children-files
 *      110 {
 *          childrenVariable = my_children_array_variable
 *          childrenFilesVariable = my_children_files
 *      }
 * }
 */
final class ContainerChildrenFilesProcessor implements DataProcessorInterface
{
    /**
     *
     * @param ContentObjectRenderer $cObj
     * @param array                 $contentObjectConfiguration
     * @param array                 $processorConfiguration
     * @param array                 $processedData
     *
     * @return array
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }

        $fetchVariable = $cObj->stdWrapValue('childrenVariable', $processorConfiguration, 'children_100');
        $fetchVariableFiles = $cObj->stdWrapValue('childrenFilesVariable', $processorConfiguration, 'files');

        $filesOfAllChildren = [];

        if (!empty($processedData[$fetchVariable])) {
            foreach ($processedData[$fetchVariable] as $child) {
                if (!empty($child[$fetchVariableFiles])) {
                    $filesOfAllChildren = array_merge($filesOfAllChildren, $child[$fetchVariableFiles]);
                }
            }
        }

        if (empty($filesOfAllChildren)) {
            return $processedData;
        }

        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'children_files');
        $processedData[$targetVariableName] = $filesOfAllChildren;

        return $processedData;
    }
}