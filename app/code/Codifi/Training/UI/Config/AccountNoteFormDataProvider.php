<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\UI\Config;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Codifi\Training\Model\ResourceModel\CustomerNote\CollectionFactory;

/**
 * Class AccountNoteFormDataProvider
 * @package Codifi\Training\UI\Config
 */
class AccountNoteFormDataProvider extends AbstractDataProvider
{
    /**
     * Loaded data
     *
     * @var array
     */
    private $loadedData;

    /**
     * AccountNoteFormDataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): ?array
    {
        if ($this->loadedData === null) {
            $items = $this->collection->getItems();

            foreach ($items as $item) {
                $this->loadedData[$item->getId()] = $item->getData();
            }
        }

        return $this->loadedData;
    }
}
