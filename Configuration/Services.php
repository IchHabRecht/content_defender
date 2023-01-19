<?php

declare(strict_types=1);

use IchHabRecht\ContentDefender\DependencyInjection\ContentDefenderIntegrationsPass;
use IchHabRecht\ContentDefender\Repository\RecordRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->registerForAutoconfiguration(RecordRepositoryInterface::class)->addTag('content_defender.recordRepository');
    $containerBuilder->addCompilerPass(new ContentDefenderIntegrationsPass('content_defender.recordRepository'));
};
