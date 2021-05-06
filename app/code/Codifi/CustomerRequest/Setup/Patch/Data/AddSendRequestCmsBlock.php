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
 * Class AddSendRequestCmsBlock
 * @package Codifi\CustomerRequest\Setup\Patch\Data
 */
class AddSendRequestCmsBlock implements DataPatchInterface
{
    /**
     * Block is active
     */
    const BLOCK_IS_ACTIVE = 1;

    /**
     * Block factory
     *
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * Block repository
     *
     * @var BlockRepository
     */
    private $blockRepository;

    /**
     * AddSendRequestCmsBlock constructor.
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
            'title' => 'Send Request',
            'identifier' => 'send-request',
            'stores' => ['0'],
            'is_active' => self::BLOCK_IS_ACTIVE,
            'content' => '<div class="cms-terms">
    <p class="greeting">{{trans "Thank you for visiting %store_name" store_name=$store.frontend_name}}.</p>
    </div>
    <div class="cms-terms">Our customer service team will gladly help with any queries.</div>'
        ];
        $newBlock = $this->blockFactory->create(['data' => $data]);

        $this->blockRepository->save($newBlock);
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): string
    {
        return '';
    }
}
