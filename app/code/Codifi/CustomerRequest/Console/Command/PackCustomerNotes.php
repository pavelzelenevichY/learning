<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Codifi\CustomerRequest\Model\ExportCsv;
use Magento\Framework\Exception\NoSuchEntityException;
use Codifi\CustomerRequest\Helper\Config;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class PackCustomerNotes
 * @package Codifi\CustomerRequest\Console
 */
class PackCustomerNotes extends Command
{
    /**
     * Period
     */
    const KEY_PERIOD = 'period';

    /**
     * Export to csv file
     *
     * @var ExportCsv
     */
    private $exportStatus;

    /**
     * Config
     *
     * @var Config
     */
    private $config;

    /**
     * PackCustomerNotes constructor.
     *
     * @param ExportCsv $exportStatus
     * @param Config $config
     */
    public function __construct(
        ExportCsv $exportStatus,
        Config $config
    ) {
        $this->exportStatus = $exportStatus;
        $this->config = $config;
        parent::__construct();
    }

    /**
     * Configures the current command
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::KEY_PERIOD,
                null,
                InputOption::VALUE_OPTIONAL,
                'period'
            )
        ];

        $this->setName('codifi:archive:notes');
        $this->setDescription('Archive customer notes that haven\'t updates for N (period) months');
        $this->setDefinition($options);

        parent::configure();
    }

    /**
     * Executes the current command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption(self::KEY_PERIOD)) {
            $period = $this->config->getPeriod();
        } elseif (is_int($input->getOption(self::KEY_PERIOD))) {
            $period = $input->getOption(self::KEY_PERIOD);
        } else {
            throw new LocalizedException(__("Input value must have integer type"));
        }

        $export = $this->exportStatus->export($period);

        if ($export === 'success') {
            $format = "Customer notes that haven't updates %s months archived.";
            $output->writeln(sprintf($format, $period));
        } else {
            $output->writeln($export);
        }
    }
}
