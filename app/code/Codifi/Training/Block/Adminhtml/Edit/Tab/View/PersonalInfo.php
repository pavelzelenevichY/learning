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
    private $adminSessionManagement;

    /**
     * PersonalInfo constructor.
     *
     * @param Context $context
     * @param AdminSessionManagement $adminSessionManagement
     * @param array $data
     */
    public function __construct(
        Context $context,
        AdminSessionManagement $adminSessionManagement,
        array $data = []
    ) {
        $this->adminSessionManagement = $adminSessionManagement;
        parent::__construct($context, $data);
    }

    /**
     * Check parameters.
     *
     * @return bool
     */
    public function checkForOneTimeDemoMessage(): bool
    {
        return $this->adminSessionManagement->checkForOneTimeDemoMessage();
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->adminSessionManagement->getMessageAndSetCustomerIdToAdminSession();
    }
}
