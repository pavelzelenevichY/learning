<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Plugin\Magento\Quote\Api;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Codifi\Sales\Helper\Config;

/**
 * Class CartRepositoryInterfacePlugin
 * @package Codifi\Sales\Plugin\Magento\Quote\Api
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
     * CartRepositoryInterfacePlugin constructor
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
        $orderType = $quote->getData(Config::ORDER_TYPE_CODE);
        $quoteExtensionAttributes = $quote->getExtensionAttributes();
        if ($quoteExtensionAttributes === null) {
            $quoteExtensionAttributes = $this->quoteExtensionFactory->create();
        }
        $quoteExtensionAttributes->setData(Config::ORDER_TYPE_CODE, $orderType);
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
    public function afterGetList(
        CartRepositoryInterface $subject,
        CartSearchResultsInterface $searchData
    ): CartSearchResultsInterface {
        $quotes = $searchData->getItems();
        foreach ($quotes as $quote) {
            $orderType = $quote->getData(Config::ORDER_TYPE_CODE);
            $quoteExtensionAttributes = $quote->getExtensionAttributes();
            if ($quoteExtensionAttributes === null) {
                $quoteExtensionAttributes = $this->quoteExtensionFactory->create();
            }
            $quoteExtensionAttributes->setData(Config::ORDER_TYPE_CODE, $orderType);
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
        $quoteExtensionAttributes = $quote->getExtensionAttributes();
        if ($quoteExtensionAttributes) {
            $orderType = $quoteExtensionAttributes->getOrderType();
            if ($orderType) {
                $quote->setData(Config::ORDER_TYPE_CODE, $orderType);
            }
        }

        return [$quote];
    }
}
