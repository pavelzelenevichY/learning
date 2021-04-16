<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Model;

use Codifi\Sales\Api\Data\OrderTypeAttributeInterface;

/**
 * Class OrderTypeAttribute
 * @package Codifi\Sales\Model
 */
class OrderTypeAttribute implements OrderTypeAttributeInterface
{
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    public function setValue($value)
    {
        $this->setData(self::VALUE, $value);
    }
}
