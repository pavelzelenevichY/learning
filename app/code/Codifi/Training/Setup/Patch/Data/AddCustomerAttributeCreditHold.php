<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Model\ResourceModel\Attribute as AttributeResourceModel;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Customer\Model\Customer;
use Codifi\Training\Model\Source\CustomSelect;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class AddCustomerAttributeCreditHold
 * @package Codifi\Training\Setup\Patch\Data
 */
class AddCustomerAttributeCreditHold implements DataPatchInterface
{
    /**
     * Constant code of custom customer attribute credit_hold.
     *
     * @var string
     */
    const ATTRIBUTE_CODE = 'credit_hold';

    /**
     * Constant label of custom customer attribute credit_hold.
     *
     * @var string
     */
    const ATTRIBUTE_LABEL = 'Credit Hold';

    /**
     * An additional pre-configuration environment that creates a database connection.
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * Customer setup factory.
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * Attribute Set Factory.
     *
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * Attribute resource model.
     *
     * @var AttributeResourceModel
     */
    private $attributeResourceModel;

    /**
     * AddCustomerAttributeCereditHold Constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     * @param AttributeResourceModel $attributeResourceModel
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        AttributeResourceModel $attributeResourceModel
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->attributeResourceModel = $attributeResourceModel;
    }

    /**
     * Add 'Credit Hold' customer attribute.
     *
     * @throws AlreadyExistsException
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function apply() : void
    {
        $moduleDataSetupConnection = $this->moduleDataSetup->getConnection();
        $moduleDataSetupConnection->startSetup();

        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerSetEavConfig = $customerSetup->getEavConfig();

        $customerEntity = $customerSetEavConfig->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'int',
                'label' => self::ATTRIBUTE_LABEL,
                'input' => 'select',
                'source' => CustomSelect::class,
                'sort_order' => '30',
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '0',
                'unique' => false,
                'system' => false,
                'is_visible_in_grid' => true,
                'is_used_in_grid' => true,
                'is_filterable_in_grid' => true,
                'is_searchable_in_grid' => true,
            ]
        );

        $getAttribute = $customerSetEavConfig->getAttribute(Customer::ENTITY, self::ATTRIBUTE_CODE);
        $attribute = $getAttribute->addData([
            'attribute_set_id' => $attributeSetId,
            'attribute_group_id' => $attributeGroupId,
            'used_in_forms' => [
                'adminhtml_customer'
            ]
        ]);

        $this->attributeResourceModel->save($attribute);

        $moduleDataSetupConnection->endSetup();
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
