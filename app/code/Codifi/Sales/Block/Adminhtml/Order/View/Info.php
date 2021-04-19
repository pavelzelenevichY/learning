<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Block\Adminhtml\Order\View;

use Magento\Sales\Block\Adminhtml\Order\AbstractOrder;
use Magento\Sales\Model\OrderRepository;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Helper\Admin;


/**
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @since 100.0.2
 */
class Info extends AbstractOrder
{
    /**
     * Order repository
     *
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    public function __construct(
        Context $context,
        Registry $registry,
        Admin $adminHelper,
        OrderRepository $orderRepository,
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        $this->coreRegistry = $registry;
        parent::__construct($context, $registry, $adminHelper, $data);
    }

    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }

    public function getAttributeValue()
    {
        $order = $this->getOrder();
        $attributes = $order->getExtensionAttributes();
        $orderTypeValue = $attributes->getOrderType();

        return $orderTypeValue;
    }
}
