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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

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
     * Info constructor.
     *
     * @param Config $orderTypeSource
     */
    public function __construct(
        Config $orderTypeSource
    ) {
        $this->orderTypeSource = $orderTypeSource;
    }

    /**
     * Get order_type attrubute label
     *
     * @param Order $currentOrder
     * @return string
     * @throws LocalizedException
     */
    public function getAttributeLabel(Order $currentOrder): string
    {
        $attributeLabel = '';
        $orderType = $currentOrder->getData(Config::ORDER_TYPE_CODE);
        $options = $this->orderTypeSource->getAllOptionsOrderType();
        foreach ($options as $item) {
            if ($item['value'] === $orderType) {
                $attributeLabel = $item['description'] ?? $item['value'];
            }
        }

        return $attributeLabel;
    }
}
