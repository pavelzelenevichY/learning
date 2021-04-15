<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Block\Adminhtml\Edit\Tab\View;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Codifi\Training\Model\AdminSessionManagement;

/**
 * Class PersonalInfo
 * @package Codifi\Training\Block\Adminhtml\Edit\Tab\View
 */
class PersonalInfo extends Template
{
    /**
     * Admin session.
     *
     * @var AdminSessionManagement
     */
    private $adminSession;

    /**
     * PersonalInfo constructor.
     *
     * @param Context $context
     * @param AdminSessionManagement $adminSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        AdminSessionManagement $adminSession,
        array $data = []
    ) {
        $this->adminSession = $adminSession;
        parent::__construct($context, $data);
    }

    /**
     * Check parameters.
     *
     * @return bool
     */
    public function checkForOneTimeDemoMessage() : bool
    {
        return $this->adminSession->checkForOneTimeDemoMessage();
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage() : string
    {
        return $this->adminSession->getMessageAndSetCustomerIdToAdminSession();
    }
}
