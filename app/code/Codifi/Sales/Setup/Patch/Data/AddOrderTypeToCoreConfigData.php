<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class AddOrderTypeToCoreConfigData
 * @package Codifi\Sales\Setup\Patch\Data
 */
class AddOrderTypeToCoreConfigData implements DataPatchInterface
{
    /**
     * Serializer interface
     *
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Writer Interface
     *
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * Path to config
     */
    const XML_PATH_ORDER_TYPE_VALUE = 'codifi_sales/order/order_type';

    /**
     * Order type values
     */
    const ORDER_TYPE_VALUE = [
        [
            'value' => 'REGULAR',
            'description' => 'Regular Order',
            'selected' => '1'
        ],
        [
            'value' => 'CREDIT_HOLD',
            'description' => 'Credit Hold Order',
            'selected' => '0'
        ]
    ];

    /**
     * AddOrderTypeToCoreConfigData constructor.
     *
     * @param WriterInterface $configWriter
     * @param SerializerInterface $serializer
     */
    public function __construct(
        WriterInterface $configWriter,
        SerializerInterface $serializer
    ) {
        $this->configWriter = $configWriter;
        $this->serializer = $serializer;
    }

    /**
     * Save order type attribute config to config_core_data table
     */
    public function apply(): void
    {
        $value = $this->serializer->serialize(self::ORDER_TYPE_VALUE);
        $this->configWriter->save(self::XML_PATH_ORDER_TYPE_VALUE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
