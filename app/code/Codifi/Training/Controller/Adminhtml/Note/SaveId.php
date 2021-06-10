<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Controller\Adminhtml\Note;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Codifi\Training\Model\AdminSessionManagement;

/**
 * Class SaveId
 * @package Codifi\Training\Controller\Adminhtml\Note
 */
class SaveId extends Action
{
    /**
     * Admin session management
     *
     * @var AdminSessionManagement
     */
    private $adminSessionManagement;

    /**
     * SaveId constructor.
     *
     * @param Context $context
     * @param AdminSessionManagement $adminSessionManagement
     */
    public function __construct(
        Context $context,
        AdminSessionManagement $adminSessionManagement
    ) {
        $this->adminSessionManagement = $adminSessionManagement;
        parent::__construct($context);
    }

    /**
     * Execute function
     */
    public function execute(): void
    {
        $request = $this->getRequest();
        $customerId = $request->getParam('customer_id');
        $this->adminSessionManagement->setCustomerIdToAdminSession($customerId);
    }
}
