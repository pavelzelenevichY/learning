<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Plugin\Order\Api;

use Codifi\Sales\Helper\Config;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class AddOrderTypeToOrderExtensionAttributes
 * @package Codifi\Sales\Plugin\Api
 */
class AddOrderTypeToOrderExtensionAttributes
{
    /**
     * Order Extension Attributes Factory
     *
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * AddOrderTypeToOrderExtensionAttributes constructor
     *
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(OrderExtensionFactory $orderExtensionFactory)
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * After get function add attribute to order
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        $orderType = $order->getData(Config::ORDER_TYPE_CODE);
        $orderExtensionAttributes = $order->getExtensionAttributes();
        if ($orderExtensionAttributes === null) {
            $orderExtensionAttributes = $this->orderExtensionFactory->create();
        }
        $orderExtensionAttributes->setData(Config::ORDER_TYPE_CODE, $orderType);
        $order->setExtensionAttributes($orderExtensionAttributes);

        return $order;
    }

    /**
     * After get list function add attribute to order
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult): OrderSearchResultInterface
    {
        $orders = $searchResult->getItems();
        foreach ($orders as &$order) {
            $orderType = $order->getData(Config::ORDER_TYPE_CODE);
            $orderExtensionAttributes = $order->getExtensionAttributes();
            if ($orderExtensionAttributes === null) {
                $orderExtensionAttributes = $this->orderExtensionFactory->create();
            }
            $orderExtensionAttributes->setData(Config::ORDER_TYPE_CODE, $orderType);
            $order->setExtensionAttributes($orderExtensionAttributes);
        }

        return $searchResult;
    }

    /**
     * Save to sales_order table order_type attribute
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $quote
     * @return array
     */
    public function beforeSave(OrderRepositoryInterface $subject, OrderInterface $order): array
    {
        $orderExtensionAttributes = $order->getExtensionAttributes();
        $orderType = $orderExtensionAttributes->getOrderType();
        $order->setData(Config::ORDER_TYPE_CODE, $orderType);

        return [$order];
    }
}
