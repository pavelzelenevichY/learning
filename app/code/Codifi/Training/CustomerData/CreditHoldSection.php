<?php
/**
<<<<<<< HEAD
 * Codifi_Training
=======
 * Codifi_CustomerRequest
>>>>>>> 29f892be72c3d1bb34daeb7d42e8d573bf90400e
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Codifi\Training\Setup\Patch\Data\AddCustomerAttributeCreditHold;

/**
 * Class CreditHoldSection
 * @package Codifi\Training\CustomerData
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
        $creditHoldAttribute = $customerData->getCustomAttribute(AddCustomerAttributeCreditHold::ATTRIBUTE_CODE);

        $creditHoldAttributeValue = false;
        if ($creditHoldAttribute) {
            $creditHoldAttributeValue = (bool)$creditHoldAttribute->getValue();
        }

        return $creditHoldAttributeValue;
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
