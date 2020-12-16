<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\BackendLayout;

/*
 * This file is part of the TYPO3 extension content_defender.
 *
 * (c) Nicole Cordes <typo3@cordes.co>
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

class Context
{
    /**
     * @var int
     */
    protected $pageUid = 0;

    /**
     * @var int
     */
    protected $colPos = 0;

    /**
     * @var int
     */
    protected $recordUid = 0;

    public function __construct(int $pageUid, int $colPos, int $recordUid = 0)
    {
        $this->pageUid = $pageUid;
        $this->colPos = $colPos;
        $this->recordUid = $recordUid;
    }

    public function getPageUid(): int
    {
        return $this->pageUid;
    }

    public function getColPos(): int
    {
        return $this->colPos;
    }

    public function getRecordUid(): int
    {
        return $this->recordUid;
    }
}
