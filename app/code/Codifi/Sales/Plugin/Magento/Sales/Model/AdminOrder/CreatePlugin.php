<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Plugin\Magento\Sales\Model\AdminOrder;

use Codifi\Sales\Helper\Config;
use Magento\Sales\Model\AdminOrder\Create;

/**
 * Class CreatePlugin
 * @package Codifi\Sales\Plugin\AdminOrder
 */
class CreatePlugin
{
    /**
     * Set order_type attribute value to quote
     *
     * @param Create $subject
     * @param Create $result
     * @param array $data
     * @return Create
     */
    public function afterImportPostData(
        Create $subject,
        Create $result,
        array $data
    ): Create
    {
        if (isset($data[Config::ORDER_TYPE_CODE])) {
            $quote = $subject->getQuote();
            $quote->setData(Config::ORDER_TYPE_CODE, $data[Config::ORDER_TYPE_CODE]);
        }

        return $result;
    }
}
