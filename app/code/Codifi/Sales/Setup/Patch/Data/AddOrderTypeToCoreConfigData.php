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
    const ORDER_TYPE_XML_PATH = 'codifi_sales/order/order_type';

    /**
     * Order type values
     */
    private $orderTypeValue = [
        [
            'value' => 'REGULAR',
            'label' => 'Regular Order'
        ],
        [
            'value' => 'CREDIT_HOLD',
            'label' => 'Credit Hold Order'
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
        $value = $this->serializer->serialize($this->orderTypeValue);
        $this->configWriter->save(self::ORDER_TYPE_XML_PATH, $value);
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
