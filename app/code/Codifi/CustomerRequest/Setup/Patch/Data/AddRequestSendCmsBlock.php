<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\BlockRepository;

/**
 * Class AddRequestSendCmsBlock
 * @package Codifi\CustomerRequest\Setup\Patch\Data
 */
class AddRequestSendCmsBlock implements DataPatchInterface
{
    /**
     * Block factory
     *
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * Block repository
     *
     * @var BlockRepository
     */
    protected $blockRepository;

    /**
     * AddRequestSendCmsBlock constructor.
     *
     * @param BlockFactory $blockFactory
     * @param BlockRepository $blockRepository
     */
    public function __construct(
        BlockFactory $blockFactory,
        BlockRepository $blockRepository
    ) {
        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
    }

    /**
     * Add custom send request CMS block
     *
     * @throws CouldNotSaveException
     */
    public function apply(): void
    {
        $data = [
            'title' => 'Send request',
            'identifier' => 'send-request',
            'stores' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            'is_active' => 1,
            'content' => '<div class="cms-terms">Thank you for visiting {{store_name}}.</div>
                          <div class="cms-terms">Our customer service team will gladly help with any queries.</div>'
        ];
        $newBlock = $this->blockFactory->create(['data' => $data]);

        $this->blockRepository->save($newBlock);
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
