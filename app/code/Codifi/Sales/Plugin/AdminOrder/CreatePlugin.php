<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Plugin\AdminOrder;

use Codifi\Sales\Helper\Config;
use Magento\Sales\Model\AdminOrder\Create;

/**
 * Class CreatePlugin
 * @package Codifi\Sales\Plugin\AdminOrder
 */
class CreatePlugin
{
    /**
     * @param Create $subject
     * @param Create $result
     * @param $data
     * @return Create
     */
    public function afterImportPostData(Create $subject,Create $result, $data): Create
    {
        if (isset($data[Config::ORDER_TYPE_CODE])) {
            $quote = $subject->getQuote();
            $quote->setData(Config::ORDER_TYPE_CODE, $data[Config::ORDER_TYPE_CODE]);
        }

        return $result;
    }
}
