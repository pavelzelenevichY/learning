<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\ViewModel\Order\View;

use Magento\Sales\Model\Order;
use Codifi\Sales\Helper\Config;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Codifi\Training\Model\ConfigProvider;

/**
 * Class Info
 * @package Codifi\Sales\ViewModel\Order\View
 */
class Info implements ArgumentInterface
{
    /**
     * Order type options
     *
     * @var Config
     */
    private $orderTypeSource;

    /**
     * Customer config provider
     *
     * @var ConfigProvider
     */
    private $customerConfigProvider;

    /**
     * Info constructor.
     *
     * @param Config $orderTypeSource
     * @param ConfigProvider $customerConfigProvider
     */
    public function __construct(
        Config $orderTypeSource,
        ConfigProvider $customerConfigProvider
    ) {
        $this->orderTypeSource = $orderTypeSource;
        $this->customerConfigProvider = $customerConfigProvider;
    }

    /**
     * Get order_type attrubute label
     *
     * @param Order $currentOrder
     * @return string
     */
    public function getAttributeLabel(Order $currentOrder): string
    {
        $orderType = $currentOrder->getData(Config::ORDER_TYPE_CODE);
        if ($orderType) {
            $label = $this->orderTypeSource->getAttributeLabel($orderType);
        } else {
            $label = '';
        }

        return $label;
    }
}
