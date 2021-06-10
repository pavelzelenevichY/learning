<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\ViewModel\Admin;

use Codifi\Training\Model\ConfigProvider;
use Codifi\Training\Setup\Patch\Data\AddCustomerAttributeCreditHold;
use Magento\Backend\Model\Session as BackendSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Codifi\Training\Model\AdminSessionManagement;

/**
 * Class AdminSessionManagementViewModel
 * @package Codifi\Training\ViewModel\Admin
 */
class AdminSessionManagementViewModel implements ArgumentInterface
{
    /**
     * Admin session attribute customers id.
     */
    const ADMIN_SESSION_ATTRIBUTE_CUSTOMER_IDS = 'customers_id';

    /**
     * Backend session.
     *
     * @var BackendSession
     */
    private $backendSession;

    /**
     * Config provider.
     *
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * Admin session management
     *
     * @var AdminSessionManagement
     */
    private $adminSessionManagement;

    /**
     * AdminSessionManagementViewModel constructor.
     *
     * @param ConfigProvider $configProvider
     * @param BackendSession $backendSession
     * @param AdminSessionManagement $adminSessionManagement
     */
    public function __construct(
        ConfigProvider $configProvider,
        BackendSession $backendSession,
        AdminSessionManagement $adminSessionManagement
    ) {
        $this->configProvider = $configProvider;
        $this->backendSession = $backendSession;
        $this->adminSessionManagement = $adminSessionManagement;
    }

    /**
     * Get credit_hold attribute value from current customer.
     *
     * @return bool
     */
    private function getCustomerAttrCreditHold(): bool
    {
        $customerData = $this->backendSession->getCustomerData();

        return isset($customerData['account'][AddCustomerAttributeCreditHold::ATTRIBUTE_CODE]) &&
            $customerData['account'][AddCustomerAttributeCreditHold::ATTRIBUTE_CODE];
    }

    /**
     * Check to be customer id in admin session array.
     *
     * @return bool
     */
    public function isCustomerIdInAdminSession(): bool
    {
        $currentCustomerId = $this->adminSessionManagement->getCustomerId();
        $customerIds = $this->adminSessionManagement->getCustomerIds();
        $isCustomerIdInArray = false;
        if (!empty($customerIds) && in_array($currentCustomerId, $customerIds)) {
            $isCustomerIdInArray = true;
        }

        return $isCustomerIdInArray;
    }

    /**
     * Get options enabled.
     *
     * @return bool
     */
    public function isCreditHoldConfigEnabled(): bool
    {
        return $this->configProvider->isOptionCreditHoldEnabled();
    }

    /**
     * Check customer attribute, option enabled and customer id in admin session for once show popup
     *
     * @return bool
     */
    public function checkBeforeDemo(): bool
    {
        return $this->getCustomerAttrCreditHold()
            && $this->isCreditHoldConfigEnabled()
            && !$this->isCustomerIdInAdminSession();
    }

    /**
     * Get message and set customer id to admin session.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->configProvider->getMessage() ?? '';
    }

    /**
     * Set customer id to array in admin session.
     *
     * @param int|null $customerId
     */
    public function setCustomerIdToAdminSession(int $customerId = null): void
    {
        $this->adminSessionManagement->setCustomerIdToAdminSession($customerId);
    }
}
