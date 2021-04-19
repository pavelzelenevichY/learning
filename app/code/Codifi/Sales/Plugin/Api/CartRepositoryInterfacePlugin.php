<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Plugin\Api;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartSearchResultsInterface;

/**
 * Class CartRepositoryInterfacePlugin
 * @package Codifi\Sales\Plugin\Api
 */
class CartRepositoryInterfacePlugin
{
    /**
     * Cart Extension Attributes Factory
     *
     * @var CartExtensionFactory
     */
    private $quoteExtensionFactory;

    /**
     * OrderRepositoryPlugin constructor
     *
     * @param CartExtensionFactory $quoteExtensionFactory
     */
    public function __construct(CartExtensionFactory $quoteExtensionFactory)
    {
        $this->quoteExtensionFactory = $quoteExtensionFactory;
    }

    /**
     * After get function add order type attribute to quote
     *
     * @param CartRepositoryInterface $subject
     * @param CartInterface $quote
     * @return CartInterface
     */
    public function afterGet(CartRepositoryInterface $subject, CartInterface $quote): CartInterface
    {
        $orderType = $quote->getData('order_type');
        $quoteExtensionAttributes = $quote->getExtensionAttributes();
        if ($quoteExtensionAttributes === null) {
            $quoteExtensionAttributes = $this->quoteExtensionFactory->create();
        }
        $quoteExtensionAttributes->setData('order_type', $orderType);
        $quote->setExtensionAttributes($quoteExtensionAttributes);

        return $quote;
    }

    /**
     * After getList function add order type attribute to quote
     *
     * @param CartRepositoryInterface $subject
     * @param CartSearchResultsInterface $searchData
     * @return CartSearchResultsInterface
     */
    public function afterGetList(CartRepositoryInterface $subject, CartSearchResultsInterface $searchData): CartSearchResultsInterface
    {
        $quotes = $searchData->getItems();
        foreach ($quotes as $quote) {
            $orderType = $quote->getData('order_type');
            $quoteExtensionAttributes = $quote->getExtensionAttributes();
            if ($quoteExtensionAttributes === null) {
                $quoteExtensionAttributes = $this->quoteExtensionFactory->create();
            }
            $quoteExtensionAttributes->setData('order_type', $orderType);
            $quote->setExtensionAttributes($quoteExtensionAttributes);
        }

        return $searchData;
    }

    /**
     * Save to quote table order_type attribute
     *
     * @param CartRepositoryInterface $subject
     * @param CartInterface $quote
     * @return array
     */
    public function beforeSave(CartRepositoryInterface $subject, CartInterface $quote): array
    {
        $orderType = $quote->getExtensionAttributes()->getOrderType();
        $quote->setData('order_type', $orderType);

        return [$quote];
    }
}
