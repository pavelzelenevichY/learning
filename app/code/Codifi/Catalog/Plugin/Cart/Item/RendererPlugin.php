<?php
/**
 * Codifi_Catalog
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Catalog\Plugin\Cart\Item;

use Magento\Checkout\Block\Cart\Item\Renderer;

/**
 * Class RendererPlugin
 * @package Codifi\Catalog\Plugin\Cart\Item
 */
class RendererPlugin
{
    /**
     * Check Product has URL
     *
     * @param Renderer $subject
     * @param bool $result
     * @return bool
     */
    public function afterHasProductUrl(Renderer $subject, bool $result): bool
    {
        return false;
    }
}
