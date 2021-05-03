<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Block\Adminhtml\Order\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Codifi\Sales\Helper\Config;
use Magento\Backend\Block\Context;
use Magento\Framework\DataObject;

/**
 * Class Type
 * @package Codifi\Sales\Block\Adminhtml\Order\Renderer
 */
class Type extends AbstractRenderer
{
    /**
     * Order type config
     *
     * @var Config
     */
    private $orderTypeSource;

    /**
     * Type constructor.
     *
     * @param Context $context
     * @param Config $orderTypeSource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $orderTypeSource,
        array $data = []
    ) {
        $this->orderTypeSource = $orderTypeSource;
        parent::__construct($context, $data);
    }

    /**
     * Render order type label
     *
     * @param DataObject $row
     * @return string
     */
    public function render(DataObject $row): string
    {
        $orderType = $row->getOrderType();
        $attributeLabel = $this->getAttributeLabel($orderType);

        return $attributeLabel;
    }

    /**
     * Get order_type attribute label
     *
     * @param $orderType
     * @return string
     */
    public function getAttributeLabel($orderType): string
    {
        $attributeLabel = '';
        $options = $this->orderTypeSource->getAllOptionsOrderType();
        foreach ($options as $item) {
            if ($item['value'] === $orderType) {
                $attributeLabel = $item['description'] ?? $item['value'];
            }
        }

        return $attributeLabel;
    }
}
