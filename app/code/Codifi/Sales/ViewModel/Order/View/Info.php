<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\ViewModel\Order\View;

use Codifi\Sales\Model\Source\OrderType;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Info
 * @package Codifi\Sales\ViewModel\Order\View
 */
class Info implements ArgumentInterface
{
    /**
     * Order type options
     *
     * @var OrderType
     */
    private $orderTypeSource;

    /**
     * Info constructor.
     *
     * @param OrderType $orderTypeSource
     */
    public function __construct(
        OrderType $orderTypeSource
    ){
        $this->orderTypeSource = $orderTypeSource;
    }


    /**
     * Get order_type attrubute label
     *
     * @param Order $currentOrder
     * @return string
     * @throws LocalizedException
     */
    public function getAttributeLabel($currentOrder): string
    {
        $response = '';
        $orderType = $currentOrder->getData('order_type');
        $options = $this->orderTypeSource->getAllOptions();
        foreach ($options as $item){
            if ($item['value'] === $orderType) {
                $response = $item['description'];
            }
        }

        return $response;
    }
}
