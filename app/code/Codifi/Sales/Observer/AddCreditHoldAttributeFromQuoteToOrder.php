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

/**
 * Class AddCreditHoldAttributeFromQuoteToOrder
 * @package Codifi\Sales\Observer
 */
class AddCreditHoldAttributeFromQuoteToOrder implements ObserverInterface
{
    /**
     * Execute function.
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer) : void
    {
        $event = $observer->getEvent();
        $dispatch = $event->dispatch();
        $quote = $dispatch->getData('quote');
        $quoteOrderType = $quote->getData('order_type');
        $order = $dispatch->getData('order');
        $order->setData('order_type', $quoteOrderType);
    }
}
