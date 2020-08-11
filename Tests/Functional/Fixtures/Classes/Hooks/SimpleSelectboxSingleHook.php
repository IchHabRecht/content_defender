<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Tests\Functional\Fixtures\Classes\Hooks;

class SimpleSelectboxSingleHook
{
    public function addSimpleSelectboxItems(array &$parameters)
    {
        $parameters['items'] = [
            0 => [
                1 => '0',
            ],
            1 => [
                0 => 'tx_simpleselectboxsingle.I.1',
                1 => '1',
            ],
            2 => [
                0 => 'tx_simpleselectboxsingle.I.2',
                1 => '2',
            ],
            3 => [
                0 => 'tx_simpleselectboxsingle.I.3',
                1 => '3',
            ],
            4 => [
                0 => 'tx_simpleselectboxsingle.I.4',
                1 => '4',
            ],
            5 => [
                0 => 'tx_simpleselectboxsingle.I.5',
                1 => '5',
            ],
            6 => [
                0 => 'tx_simpleselectboxsingle.I.6',
                1 => '6',
            ],
            7 => [
                0 => 'tx_simpleselectboxsingle.I.7',
                1 => '7',
            ],
        ];
    }
}
