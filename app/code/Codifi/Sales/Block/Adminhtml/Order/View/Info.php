<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Block\Adminhtml\Order\View;

use Magento\Sales\Block\Adminhtml\Order\AbstractOrder;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Helper\Admin;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Info
 * @package Codifi\Sales\Block\Adminhtml\Order\View
 */
class Info extends AbstractOrder
{
    /**
     * Options
     *
     * @var array[]
     */
    private $options = [
        ['value' => 'REGULAR', 'label' => 'Regular Order', 'selected' => 1],
        ['value' => 'CREDIT_HOLD', 'label' => 'Credit Hold Order', 'selected' => 0]
    ];

    /**
     * Info constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Admin $adminHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Admin $adminHelper,
        array $data = []
    ){
        parent::__construct(
            $context,
            $registry,
            $adminHelper,
            $data
        );
    }

    /**
     * Get order_type attribute value
     *
     * @return array
     * @throws LocalizedException
     */
    public function getAttributeValue()
    {
        $response = [];
        $order = $this->getOrder();
        $orderType = $order->getData('order_type');
        $options = $this->options;
        foreach ($options as $item){
            if ($item['value'] === $orderType) {
                $response[] = $item;
            }
        }

        return $response;
    }
}
