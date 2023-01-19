<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\DependencyInjection;

use IchHabRecht\ContentDefender\Repository\ContentRepository;
use IchHabRecht\ContentDefender\Repository\RecordRepository;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ContentDefenderIntegrationsPass implements CompilerPassInterface
{
    protected string $defaultRecordRepository = RecordRepository::class;

    protected string $tagName;

    public function __construct(string $tagName)
    {
        $this->tagName = $tagName;
    }

    public function process(ContainerBuilder $container): void
    {
        $repositories = $this->collectRepositories($container);
        $contentRepository = $container->findDefinition(ContentRepository::class);
        $contentRepository->setArgument('$recordRepositories', $repositories);
    }

    protected function collectRepositories(ContainerBuilder $container): array
    {
        $repositories = [];
        foreach ($container->findTaggedServiceIds($this->tagName) as $repositoryName => $tags) {
            $repository = $container->findDefinition($repositoryName);
            if (!$repository->isAutoconfigured()
                || $repository->isAbstract()
            ) {
                continue;
            }
            foreach ($tags as $attributes) {
                if (($attributes['disabled'] ?? false) === true) {
                    continue;
                }
                $integrationIdentifier = (string)($attributes['identifier'] ?? $repositoryName);
                $repositories[$integrationIdentifier] = $repository;
            }
        }

        if (!isset($repositories[$this->defaultRecordRepository])) {
            $repository = $container->findDefinition($this->defaultRecordRepository);
            $repository->setPublic(true);
            $repositories[$this->defaultRecordRepository] = $repository;
        }

        return $repositories;
    }
}
