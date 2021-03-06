<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Block\Adminhtml\Edit\Tab;

use Magento\Customer\Block\Adminhtml\Edit\Tab\Orders as MagentoCustomerOrders;
use Codifi\Sales\Block\Adminhtml\Order\Renderer\Type;
use Magento\Sales\Block\Adminhtml\Reorder\Renderer\Action;

/**
 * Class Orders
 * @package Codifi\Sales\Block\Adminhtml\Edit\Tab
 */
class Orders extends MagentoCustomerOrders
{
    /**
     * Add order_type label to column
     */
    protected function _prepareCollection(): void
    {
        parent::_prepareCollection();

        $collection = $this->getCollection();
        $collection->addFieldToSelect('order_type');
        $collection->clear();
        $collection->load();

        $this->setCollection($collection);
    }

    /**
     * Add order_type label to column
     */
    protected function _prepareColumns(): void
    {
        parent::_prepareColumns();

        $this->removeColumn('action');

        $this->addColumn(
            'order_type',
            [
                'header' => 'Type',
                'index' => 'order_type',
                'width' => '100px',
                'renderer' => Type::class
            ]
        );

        if ($this->_salesReorder->isAllow()) {
            $this->addColumn(
                'action',
                [
                    'header' => 'Action',
                    'filter' => false,
                    'sortable' => false,
                    'width' => '100px',
                    'renderer' => Action::class
                ]
            );
        }
    }
}
