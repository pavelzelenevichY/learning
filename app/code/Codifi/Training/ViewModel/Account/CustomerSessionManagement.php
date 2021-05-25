<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\ViewModel\Account;

use Codifi\Training\Model\ConfigProvider;
use Codifi\Training\Setup\Patch\Data\AddCustomerAttributeCreditHold;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\UrlInterface;

/**
 * Class CustomerSessionManagement
 * @package Codifi\Training\ViewModel\Account
 */
class CustomerSessionManagement implements ArgumentInterface
{
    /**
     * Customer session.
     *
     * @var Session
     */
    private $session;

    /**
     * Config provider.
     *
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * CustomerSessionManagement constructor.
     *
     * @param ConfigProvider $configProvider
     * @param Session $session
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ConfigProvider $configProvider,
        Session $session,
        UrlInterface $urlBuilder
    ) {
        $this->configProvider = $configProvider;
        $this->session = $session;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Get customer attrubute credit hold.
     *
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerAttrCreditHold(): bool
    {
        $customerData = $this->session->getCustomerData();
        $customerAttribute = $customerData->getCustomAttribute(AddCustomerAttributeCreditHold::ATTRIBUTE_CODE);
        if ($customerAttribute !== null) {
            $value = (bool)$customerAttribute->getValue();
        } else {
            $value = 0;
        }

        return $value;
    }

    /**
     * Get flag.
     *
     * @return bool
     */
    public function getFlag(): bool
    {
        return (bool)$this->session->getData(ConfigProvider::SESSION_FLAG);
    }

    /**
     * Set flag value true.
     *
     * @return void
     */
    public function setFlag(): void
    {
        $this->session->setData(ConfigProvider::SESSION_FLAG, true);
    }

    /**
     * Check for one time demo message
     *
     * @return bool
     */
    public function checkForOneTimeDemoMessage(): bool
    {
        return $this->configProvider->isOptionCreditHoldEnable() && !$this->getFlag();
    }

    /**
     * Get current customer id.
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return (int)$this->session->getCustomerId();
    }

    /**
     * Get message and call set flag function.
     *
     * @return string
     */
    public function getMessageAndCallSetFlag(): string
    {
        $this->setFlag();

        return $this->configProvider->getMessage();
    }

    /**
     * Get save url
     *
     * @return string
     */
    public function getSaveUrl(): string
    {
        return $this->urlBuilder->getUrl('customer/note/save');
    }
}
