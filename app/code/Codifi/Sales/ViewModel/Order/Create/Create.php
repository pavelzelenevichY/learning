<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\ViewModel\Order\Create;

use Codifi\Sales\Model\Source\OrderType;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class Create
 * @package Codifi\Sales\ViewModel\Order\Create
 */
class Create implements ArgumentInterface
{
    /**
     * Order type options
     *
     * @var OrderType
     */
    private $orderType;

    /**
     * Create constructor.
     *
     * @param OrderType $orderType
     */
    public function __construct(OrderType $orderType)
    {
        $this->orderType = $orderType;
    }

    /**
     * Get order_type attribute value
     *
     * @return array[]
     */
    public function getAttributeValue()
    {
        return $this->orderType->getAllOptions();
    }
}
