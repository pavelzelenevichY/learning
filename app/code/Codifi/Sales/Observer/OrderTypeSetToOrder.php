<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Codifi\Sales\Helper\Config;

/**
 * Class OrderTypeSetToOrder
 * @package Codifi\Sales\Observer
 */
class OrderTypeSetToOrder implements ObserverInterface
{
    /**
     * Execute function.
     *
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer): void
    {
        $event = $observer->getEvent();
        $quote = $event->getData('quote');
        $quoteOrderType = $quote->getData(Config::ORDER_TYPE_CODE);
        $order = $event->getData('order');
        $order->setData(Config::ORDER_TYPE_CODE, $quoteOrderType);
    }
}
