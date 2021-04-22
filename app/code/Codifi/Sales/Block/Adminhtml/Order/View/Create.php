<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Block\Adminhtml\Order\View;

use Magento\Widget\Block\BlockInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Create
 * @package Codifi\Sales\Block\Adminhtml\Order\View
 */
class Create extends Template implements BlockInterface
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

    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function getAttributeValue()
    {
        return $this->options;
    }
}
