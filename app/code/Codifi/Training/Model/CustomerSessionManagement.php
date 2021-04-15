<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Model;

use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codifi\Training\Setup\Patch\Data\AddCustomerAttributeCreditHold;

/**
 * Class CustomerSessionManagement
 * @package Codifi\Training\Model
 */
class CustomerSessionManagement
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
     * CustomerSessionManagement constructor.
     *
     * @param ConfigProvider $configProvider
     * @param Session $session
     */
    public function __construct(
        ConfigProvider $configProvider,
        Session $session
    ) {
        $this->configProvider = $configProvider;
        $this->session = $session;
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
     * Check for one time demo message.
     *
     * @return bool
     */
    public function checkForOneTimeDemoMessage(): bool
    {
        return $this->getCustomerAttrCreditHold() &&  $this->configProvider->isOptionCreditHoldEnable() && !$this->getFlag();
    }

    /**
     * Get current customer id.
     *
     * @return int
     */
    public function getCustomerId() : int
    {
        return (int)$this->session->getCustomerId();
    }

    /**
     * Get message and call set flag function.
     *
     * @return string
     */
    public function getMessageAndCallSetFlag() : string
    {
        $this->setFlag();

        return $this->configProvider->getMessage();
    }
}
