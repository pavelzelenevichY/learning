<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\ViewModel\Order\View;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order;
use Magento\Framework\Exception\LocalizedException;
use Codifi\Sales\Helper\Config;

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
    private $orderTypeConfig;

    /**
     * Info constructor.
     *
     * @param Config $orderTypeConfig
     */
    public function __construct(
        Config $orderTypeConfig
    ){
        $this->orderTypeConfig = $orderTypeConfig;
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
        $options = $this->orderTypeConfig->getAllOptions();
        foreach ($options as $item){
            if ($item['value'] === $orderType) {
                $response = $item['description'];
            }
        }

        return $response;
    }
}
