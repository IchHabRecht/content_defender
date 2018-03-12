<?php
namespace IchHabRecht\ContentDefender\Hooks;

use IchHabRecht\ContentDefender\Repository\ContentRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractDataHandlerHook
{
    /**
     * @var ContentRepository
     */
    protected $contentRepository;

    /**
     * @param ContentRepository $contentRepository
     */
    public function __construct(ContentRepository $contentRepository = null)
    {
        $this->contentRepository = $contentRepository ?? GeneralUtility::makeInstance(ContentRepository::class);
    }

    /**
     * @param array $columnConfiguration
     * @param array $record
     * @return bool
     */
    protected function isRecordAllowedByRestriction(array $columnConfiguration, array $record)
    {
        if (empty($columnConfiguration['allowed.']) && empty($columnConfiguration['disallowed.'])) {
            return true;
        }

        $allowedConfiguration = $columnConfiguration['allowed.'] ?? [];
        foreach ($allowedConfiguration as $field => $value) {
            $allowedValues = GeneralUtility::trimExplode(',', $value);
            if (!$this->isAllowedValue($record, $field, $allowedValues)) {
                return false;
            }
        }

        $disallowedConfiguration = $columnConfiguration['disallowed.'] ?? [];
        foreach ($disallowedConfiguration as $field => $value) {
            $disallowedValues = GeneralUtility::trimExplode(',', $value);
            if (!$this->isAllowedValue($record, $field, $disallowedValues, false)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $record
     * @param string $field
     * @param array $values
     * @param bool $allowed
     * @return bool
     */
    protected function isAllowedValue(array $record, $field, array $values, $allowed = true)
    {
        return !isset($record[$field])
            || ($allowed && in_array($record[$field], $values))
            || (!$allowed && !in_array($record[$field], $values));
    }

    /**
     * @param array $columnConfiguration
     * @param array $record
     * @return bool
     */
    protected function isRecordAllowedByItemsCount(array $columnConfiguration, array $record)
    {
        if (empty($columnConfiguration['maxitems'])) {
            return true;
        }

        return (int)$columnConfiguration['maxitems'] >= $this->contentRepository->addRecordToColPos($record);
    }
}
