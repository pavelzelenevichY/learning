<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Helper;

use Codifi\Sales\Setup\Patch\Data\AddOrderTypeToCoreConfigData;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class Config
 * @package Codifi\Sales\Helper
 */
class Config
{
    /**
     * Order type attribute code
     */
    const ORDER_TYPE_CODE = 'order_type';

    /**
     * @var \Magento\Framework\App\Config
     */
    private $config;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Config constructor.
     *
     * @param Config $config
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Config $config,
        SerializerInterface $serializer
    )
    {
        $this->config = $config;
        $this->serializer = $serializer;
    }

    /**
     * Get all order_type options
     *
     * @return array
     */
    public function getAllOptions(): array
    {
        $value = $this->config->getValue(AddOrderTypeToCoreConfigData::XML_PATH_ORDER_TYPE_VALUE);
        $options = $this->serializer->unserialize($value);

        return $options;
    }
}
