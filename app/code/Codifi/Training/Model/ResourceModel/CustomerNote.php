<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class CustomerNote
 * @package Codifi\Training\Model\ResourceModel
 */
class CustomerNote extends AbstractDb
{
    /**
     * CustomerNote constructor.
     */
    protected function _construct()
    {
        $this->_init('customer_note', 'note_id');
    }
}
