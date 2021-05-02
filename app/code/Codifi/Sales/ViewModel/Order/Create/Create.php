<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\ViewModel\Order\Create;

use Codifi\Sales\Helper\Config;
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
     * @var Config
     */
    private $orderTypeConfig;

    /**
     * Create constructor.
     *
     * @param Config orderTypeConfig
     */
    public function __construct(Config $orderTypeConfig)
    {
        $this->orderTypeConfig = $orderTypeConfig;
    }

    /**
     * Get order_type attribute value
     *
     * @return array
     */
    public function getOrderTypeOptions(): array
    {
        return $this->orderTypeConfig->getAllOptionsOrderType();
    }
}
