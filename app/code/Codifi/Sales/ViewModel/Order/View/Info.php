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
use Magento\Sales\Block\Adminhtml\Order\AbstractOrder;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Helper\Admin;

/**
 * Class Info
 * @package Codifi\Sales\ViewModel\Order\View
 */
class Info extends AbstractOrder implements ArgumentInterface
{
    /**
     * Order type options
     *
     * @var OrderType
     */
    private $orderType;

    /**
     * Info constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Admin $adminHelper
     * @param OrderType $orderType
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Admin $adminHelper,
        OrderType $orderType,
        array $data = []
    ){
        $this->orderType = $orderType;
        parent::__construct($context, $registry, $adminHelper, $data);
    }


    /**
     * Get order_type attribute value
     *
     * @return array[]
     */
    public function getAttributeValue()
    {
        $response = [];
        $order = $this->getOrder();
        $orderType = $order->getData('order_type');
        $options = $this->orderType->getAllOptions();
        foreach ($options as $item){
            if ($item['value'] === $orderType) {
                $response[] = $item;
            }
        }

        return $response;
    }
}
