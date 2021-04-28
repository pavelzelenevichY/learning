<?php


namespace Codifi\Sales\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;


class AddOrderTypeToCoreConfigData implements DataPatchInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * Path to config
     */
    const PATH = 'codifi_sales/order/order_type';

    /**
     * Order type values
     */
    const VALUE = [
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
        $value = $this->serializer->serialize(self::VALUE);
        $this->configWriter->save(self::PATH, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }
}
