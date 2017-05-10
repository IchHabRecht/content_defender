<?php
namespace IchHabRecht\ContentDefender\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractDataHandlerHook
{
    /**
     * @param array $columnConfiguration
     * @param string $cType
     * @return true
     */
    protected function isAllowedCType(array $columnConfiguration, $cType)
    {
        if (empty($columnConfiguration) || (empty($columnConfiguration['allowed']) && empty($columnConfiguration['disallowed']))) {
            return true;
        }

        if (!empty($columnConfiguration['allowed'])) {
            $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['allowed']);
            $allowed = in_array($cType, $cTypes, true);
        } else {
            $cTypes = GeneralUtility::trimExplode(',', $columnConfiguration['disallowed']);
            $allowed = !in_array($cType, $cTypes, true);
        }

        return $allowed;
    }
}
