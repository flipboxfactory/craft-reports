<?php

namespace flipbox\craft\reports\events;

use craft\helpers\Json;
use flipbox\craft\ember\helpers\ObjectHelper;
use flipbox\craft\reports\Reports;
use flipbox\craft\reports\reports\ReportInterface;
use yii\base\Event;
use yii\base\InvalidConfigException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RegisterReports extends Event
{
    /**
     * Event to register reports
     */
    const REGISTER_REPORTS = 'registerReports';

    /**
     * @var ReportInterface[]
     */
    public $reports = [];

    /**
     * Register a report using configured report object
     *
     * @param $report
     * @throws InvalidConfigException
     */
    public function add($report)
    {
        if (is_string($report)) {
            $report = ['class' => $report];
        }

        if (is_array($report)) {
            if (array_key_exists('handle', $report)) {
                $this->reports[$report['handle']] = $report;
                return;
            }

            $object = ObjectHelper::create($report, ReportInterface::class);
            $this->reports[$object->getHandle()] = $report;
            return;
        }

        Reports::warning(sprintf(
            "Unable to register report: %s",
            Json::encode($report)
        ));
    }
}
