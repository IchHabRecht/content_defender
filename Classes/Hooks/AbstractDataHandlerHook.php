<?php
namespace IchHabRecht\ContentDefender\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractDataHandlerHook
{
    /**
     * @param array $columnConfiguration
     * @param array $record
     * @return bool
     */
    protected function isAllowedRecord(array $columnConfiguration, array $record)
    {
        if (empty($columnConfiguration) || (empty($columnConfiguration['allowed.']) && empty($columnConfiguration['disallowed.']))) {
            return true;
        }

        if (!empty($columnConfiguration['allowed.'])) {
            foreach ($columnConfiguration['allowed.'] as $field => $value) {
                if (!isset($record[$field])) {
                    continue;
                }

                $allowedValues = GeneralUtility::trimExplode(',', $value);
                if (!in_array($record[$field], $allowedValues)) {
                    return false;
                }
            }
        }
        if (!empty($columnConfiguration['disallowed.'])) {
            foreach ($columnConfiguration['disallowed.'] as $field => $value) {
                if (!isset($record[$field])) {
                    continue;
                }

                $disallowedValues = GeneralUtility::trimExplode(',', $value);
                if (in_array($record[$field], $disallowedValues)) {
                    return false;
                }
            }
        }

        return true;
    }
}
