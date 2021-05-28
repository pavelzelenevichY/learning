<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\CustomerRequest\Model\Config\Backend;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ValueFactory;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Cron\Model\Config\Source\Frequency;
use Exception;

/**
 * Class Archive
 * @package Codifi\CustomerRequest\Model\Config\Backend
 */
class Archive extends Value
{
    /**
     * Cron string path
     */
    const CRON_CODIFI_STRING_PATH = 'crontab/archive/jobs/codifi_customerrequest_cron_archivecronmodel/schedule/cron_expr';

    /**
     * Writer Interface
     *
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * Archive constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        WriterInterface $configWriter,
        array $data = []
    ) {
        $this->configWriter = $configWriter;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * After save
     *
     * @return Archive
     * @throws Exception
     */
    public function afterSave()
    {
        $time = $this->getData('groups/archive_cron/fields/time/value');
        $frequency = $this->getData('groups/archive_cron/fields/frequency/value');

        $cronExprArray = [
            (int)$time[1], //Minute
            (int)$time[0], //Hour
            $frequency == Frequency::CRON_MONTHLY ? '1' : '*', //Day of the Month
            '*', //Month of the Year
            $frequency == Frequency::CRON_WEEKLY ? '1' : '*', //Day of the Week
        ];

        $cronExprString = join(' ', $cronExprArray);

        try {
            $this->configWriter->save(self::CRON_CODIFI_STRING_PATH, $cronExprString);
        } catch (Exception $e) {
            throw new Exception(__('We can\'t save the cron expression.'));
        }

        return parent::afterSave();
    }
}
