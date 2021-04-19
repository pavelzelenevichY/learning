<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Plugin\Api;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class OrderRepositoryInterfacePlugin
 * @package Codifi\Sales\Plugin\Api
 */
class OrderRepositoryInterfacePlugin
{
    /**
     * Order feedback field name
     */
    const FIELD_NAME = 'order_type';

    /**
     * Order Extension Attributes Factory
     *
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * OrderRepositoryPlugin constructor
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
        $orderType = $order->getData(self::FIELD_NAME);
        $orderExtensionAttributes = $order->getExtensionAttributes();
        if ($orderExtensionAttributes === null) {
            $orderExtensionAttributes = $this->orderExtensionFactory->create();
        }
        $orderExtensionAttributes->setData(self::FIELD_NAME, $orderType);
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
            $orderType = $order->getData(self::FIELD_NAME);
            $orderExtensionAttributes = $order->getExtensionAttributes();
            if ($orderExtensionAttributes === null) {
                $orderExtensionAttributes = $this->orderExtensionFactory->create();
            }
            $orderExtensionAttributes->setData(self::FIELD_NAME, $orderType);
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
        $orderType = $order->getExtensionAttributes()->getOrderType();
        $order->setData('order_type', $orderType);

        return [$order];
    }

}
