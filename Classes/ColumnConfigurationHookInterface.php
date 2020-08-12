<?php
declare(strict_types = 1);
namespace IchHabRecht\ContentDefender;

interface ColumnConfigurationHookInterface
{
    public function manipulateForTcaCTypeItems(array $columnConfiguration, array $result): array;

    public function manipulateForWizardItems(array $columnConfiguration): array;

    public function manipulateForDatamap(array $columnConfiguration, array $incomingFieldArray): array;

    public function manipulateForCmdmap(array $columnConfiguration, array $cmdmapValue): array;

}
