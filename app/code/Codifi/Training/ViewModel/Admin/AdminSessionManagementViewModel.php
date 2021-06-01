<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\ViewModel\Admin;

use Codifi\Training\Model\ConfigProvider;
use Codifi\Training\Setup\Patch\Data\AddCustomerAttributeCreditHold;
use Magento\Backend\Model\Session as BackendSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Backend\Model\Auth\Session;

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
     * Auth session.
     *
     * @var Session
     */
    private $authSession;

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
     * AdminSessionManagementViewModel constructor.
     *
     * @param ConfigProvider $configProvider
     * @param Session $authSession
     * @param BackendSession $backendSession
     */
    public function __construct(
        ConfigProvider $configProvider,
        Session $authSession,
        BackendSession $backendSession
    ) {
        $this->configProvider = $configProvider;
        $this->authSession = $authSession;
        $this->backendSession = $backendSession;
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
        $currentCustomerId = $this->getCustomerId();
        $customerIds = $this->getCustomerIds();
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
        return $this->getCustomerAttrCreditHold() &&
            $this->isCreditHoldConfigEnabled() && !$this->isCustomerIdInAdminSession();
    }

    /**
     * Get message and set customer id to admin session.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->configProvider->getMessage();
    }

    /**
     * Set customer id to array in admin session.
     *
     * @param int|null $customerId
     */
    public function setCustomerIdToAdminSession(int $customerId = null): void
    {
        if (!$customerId) {
            $customerId = $this->getCustomerId();
        }
        $customerIds = $this->getCustomerIds() ?? [];
        $customerIds[] = $customerId;
        $this->authSession->setData(self::ADMIN_SESSION_ATTRIBUTE_CUSTOMER_IDS, $customerIds);
    }

    /**
     * Get array customers id from admin session.
     *
     * @return array
     */
    public function getCustomerIds(): array
    {
        return $this->authSession->getData(self::ADMIN_SESSION_ATTRIBUTE_CUSTOMER_IDS) ?? [];
    }

    /**
     * Get current customer id from admin session.
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        $customerData = $this->backendSession->getCustomerData();

        return (int)$customerData['account']['id'] ?? 0;
    }
}
