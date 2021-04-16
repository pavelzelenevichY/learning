<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Api\Data;

/**
 * Interface OrderTypeAttributeInterface
 * @package Codifi\Sales\Api\Data
 */
interface OrderTypeAttributeInterface
{
    /**
     * Attribute value
     */
    const VALUE = 'value';

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue();

    /**
     * Set value.
     *
     * @param string $value
     * @return void
     */
    public function setValue($value);
}
