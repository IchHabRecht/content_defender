<?php

declare(strict_types=1);

namespace IchHabRecht\ContentDefender\Repository;

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

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ColPosCountState implements \ArrayAccess, \Countable, \Iterator
{
    protected FrontendInterface $cache;

    protected string $cacheIdentifier;

    protected int $position = 0;

    public function __construct(FrontendInterface $cache = null, $cacheIdentifier = 'tx_contentdefender_colPosCount', $data = null)
    {
        $this->cache = $cache ?? GeneralUtility::makeInstance(CacheManager::class)->getCache('runtime');
        $this->cacheIdentifier = $cacheIdentifier;
        if ($data !== null) {
            $this->cache->set($this->cacheIdentifier, $data);
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->cache->get($this->cacheIdentifier)[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        $data = $this->cache->get($this->cacheIdentifier);
        if ($offset === null) {
            return $data;
        }

        return $data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_array($value)) {
            $value = new self($this->cache, $this->cacheIdentifier . '_' . $offset, $value);
        }

        $cache = $this->cache->get($this->cacheIdentifier) ?: [];
        $cache[$offset] = $value;
        $this->cache->set($this->cacheIdentifier, $cache);
    }

    public function offsetUnset($offset): void
    {
        $cache = $this->cache->get($this->cacheIdentifier) ?: [];
        if (isset($cache[$offset])) {
            unset($cache[$offset]);
        }
        $this->cache->set($this->cacheIdentifier, $cache);
    }

    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->cache->get($this->cacheIdentifier) ?: []);
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        $cache = $this->cache->get($this->cacheIdentifier) ?: [];

        $value = $cache[array_keys($cache)[$this->position]] ?? null;
        if ($value instanceof self) {
            $value = $value->offsetGet(null);
        }

        return $value;
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        ++$this->position;
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        $cache = $this->cache->get($this->cacheIdentifier) ?: [];

        return array_keys($cache)[$this->position] ?? null;
    }

    #[\ReturnTypeWillChange]
    public function valid()
    {
        $cache = $this->cache->get($this->cacheIdentifier) ?: [];

        return isset(array_keys($cache)[$this->position]);
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->position = 0;
    }
}
