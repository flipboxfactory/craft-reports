<?php

namespace flipbox\craft\reports\events;

use flipbox\craft\reports\reports\ReportInterface;
use yii\base\Event;

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
}
