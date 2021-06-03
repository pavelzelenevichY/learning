<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\CustomerRequest\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\App\State;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class AddSaleCategory
 * @package Codifi\CustomerRequest\Setup\Patch\Data
 */
class AddSaleCategory implements DataPatchInterface
{
    /**
     * Store manager
     *
     * @var StoreManager
     */
    private $storeManager;

    /**
     * Category factory
     *
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * Category repository
     *
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * State
     *
     * @var State
     */
    private $state;

    /**
     * Url rewrite collection factory
     *
     * @var UrlRewriteCollectionFactory
     */
    private $urlRewriteCollectionFactory;

    /**
     * AddSaleCategory constructor.
     *
     * @param StoreManager $storeManager
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepository $categoryRepository
     * @param State $state
     * @param UrlRewriteCollectionFactory $urlRewriteCollectionFactory
     */
    public function __construct(
        StoreManager $storeManager,
        CategoryFactory $categoryFactory,
        CategoryRepository $categoryRepository,
        State $state,
        UrlRewriteCollectionFactory $urlRewriteCollectionFactory
    ) {
        $this->storeManager = $storeManager;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->state = $state;
        $this->urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
    }

    /**
     * Add new Sale category
     *
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function apply(): void
    {
        $urlKey = 'customer_request_sale';
        $row = [
            'name'            => 'Sale',
            'url_key'         => $urlKey,
            'active'          => 1,
            'is_anchor'       => 1,
            'include_in_menu' => 1,
            'display_mode'    => 'PAGE',
        ];

        $this->state->setAreaCode('adminhtml');
        $store = $this->storeManager->getStore(Store::ADMIN_CODE);
        $this->storeManager->setCurrentStore($store->getCode());

        $parentId = $this->getParentCategoryId();

        $data = [
            'parent_id'       => $parentId,
            'name'            => $row['name'],
            'is_active'       => $row['active'],
            'is_anchor'       => $row['is_anchor'],
            'include_in_menu' => $row['include_in_menu'],
            'url_key'         => $row['url_key'],
        ];

        $category = $this->categoryFactory->create();
        $defaultSetId = $category->getDefaultAttributeSetId();
        $category->setData($data);
        $category->setAttributeSetId($defaultSetId);
        $this->setAdditionalData($row, $category);
        $this->categoryRepository->save($category);
    }

    /**
     * Set additional data
     *
     * @param array    $row
     * @param Category $category
     */
    protected function setAdditionalData($row, $category): void
    {
        $additionalAttributes = [
            'position',
            'display_mode',
            'page_layout',
            'custom_layout_update',
        ];
        foreach ($additionalAttributes as $categoryAttribute) {
            if (!empty($row[$categoryAttribute])) {
                $attributeData = [$categoryAttribute => $row[$categoryAttribute]];
                $category->addData($attributeData);
            }
        }
    }

    /**
     * Get parent category id
     *
     * @return int
     */
    public function getParentCategoryId(): int
    {
        $urlCollection = $this->urlRewriteCollectionFactory->create();
        $rows = $urlCollection->getData();
        $id = 0;
        foreach ($rows as $row) {
            if ($row['entity_type'] === 'category') {
                if ($row['request_path'] === 'gear/fitness-equipment.html') {
                    $id = $row['entity_id'];
                }
            }
        }

        return $id;
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
