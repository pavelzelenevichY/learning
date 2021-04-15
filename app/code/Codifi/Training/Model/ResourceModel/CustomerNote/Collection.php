<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Model\ResourceModel\CustomerNote;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Codifi\Training\Model\CustomerNote as Model;
use Codifi\Training\Model\ResourceModel\CustomerNote as ResourceModel;

/**
 * Class Collection
 * @package Codifi\Training\Model\ResourceModel\CustomerNote
 */
class Collection extends AbstractCollection
{
    /**
     * Collection constructor.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
