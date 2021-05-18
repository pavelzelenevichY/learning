<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Codifi\Training\Setup\Patch\Data\AddCustomerAttributeCreditHold;

/**
 * Class CreditHoldSection
 * @package Codifi\CustomerRequest\CustomerData
 */
class CreditHoldSection implements SectionSourceInterface
{
    /**
     * Customer session.
     *
     * @var Session
     */
    private $customerSession;

    /**
     * CreditHoldSection constructor.
     *
     * @param Session $customerSession
     */
    public function __construct(Session $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    /**
     * Get customer attribute value credit_hold
     *
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getCustomerAttrCreditHold(): bool
    {
        $customerData = $this->customerSession->getCustomerData();
        $customerAttribute = $customerData->getCustomAttribute(AddCustomerAttributeCreditHold::ATTRIBUTE_CODE);

        return (bool)$customerAttribute->getValue() ?? false;
    }

    /**
     * Get data
     *
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getSectionData(): array
    {
        $creditHold = $this->getCustomerAttrCreditHold();

        return [
            'credit_hold' => $creditHold
        ];
    }
}
