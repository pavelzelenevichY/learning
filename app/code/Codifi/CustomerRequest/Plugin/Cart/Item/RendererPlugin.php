<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Plugin\Cart\Item;

/**
 * Class RendererPlugin
 * @package Codifi\CustomerRequest\Plugin\Cart\Item
 */
class RendererPlugin
{
    /**
     * Check Product has URL
     *
     * @return bool
     */
    public function afterHasProductUrl(): bool
    {
        return false;
    }
}
