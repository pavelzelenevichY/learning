<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Model\Source;

use Magento\Framework\App\Config;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Codifi\Sales\Setup\Patch\Data\AddOrderTypeToCoreConfigData;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class OrderType
 * @package Codifi\Sales\Model\Source
 */
class OrderType extends AbstractFieldArray
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * OrderType constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(Context $context, Config $config, SerializerInterface $serializer, array $data = [])
    {
        $this->config = $config;
        $this->serializer = $serializer;
        parent::__construct($context, $data);
    }

    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn('value', ['label' => __('value'), 'class' => 'required-entry']);
        $this->addColumn('description', ['label' => __('description'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    public function getAllOptions(): array
    {
        $value = $this->config->getValue(AddOrderTypeToCoreConfigData::PATH);
        $options = $this->serializer->unserialize($value);

        return $options;
    }
}
