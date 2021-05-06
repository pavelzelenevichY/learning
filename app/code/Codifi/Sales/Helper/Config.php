<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Codifi\Sales\Setup\Patch\Data\AddOrderTypeToCoreConfigData;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\SerializerInterface;
use \InvalidArgumentException;

/**
 * Class Config
 * @package Codifi\Sales\Helper
 */
class Config extends AbstractHelper
{
    /**
     * Order type attribute code
     */
    const ORDER_TYPE_CODE = 'order_type';

    /**
     * Order type credit hold
     */
    const ORDER_TYPE_CREDIT_HOLD_VALUE = 'CREDIT_HOLD';

    /**
     * Order type regular
     */
    const ORDER_TYPE_REGULAR_VALUE = 'REGULAR';

    /**
     * Order type label credit hold
     */
    const ORDER_TYPE_LABEL_CREDIT_HOLD = 'Credit Hold Order';

    /**
     * Serializer interface
     *
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Config constructor.
     *
     * @param Context $context
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * Get all order_type options
     *
     * @throws InvalidArgumentException
     * @return array
     */
    public function getAllOptionsOrderType(): array
    {
        $options = [];
        $value = $this->scopeConfig->getValue(AddOrderTypeToCoreConfigData::ORDER_TYPE_XML_PATH);
        if ($value) {
            $options = $this->serializer->unserialize($value);
        }

        return $options;
    }

    /**
     * Get order_type attribute label
     *
     * @param string $orderType
     * @return string
     */
    public function getAttributeLabel(string $orderType): string
    {
        $attributeLabel = '';
        $options = $this->getAllOptionsOrderType();
        foreach ($options as $item) {
            if ($item['value'] === $orderType) {
                $attributeLabel = $item['description'] ?? $item['value'];
            }
        }

        return $attributeLabel;
    }
}
