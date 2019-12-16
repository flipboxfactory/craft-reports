<?php

namespace flipbox\craft\reports\events;

use Craft;
use flipbox\craft\reports\reports\ReportInterface;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RunReport extends Event
{
    /**
     * The name of the event
     */
    const EVENT_NAME = 'runReport';

    /**
     * @var ReportInterface
     */
    public $report;

    /**
     *  Whether the report can be built.
     *
     * @var bool
     */
    public $canRun;

    /**
     * The Forbidden exception message to throw when self::canRun is false.
     *
     * @var string
     */
    public $forbiddenMessage = "Forbidden";

    /**
     * Allow ONLY admins to download everything by default
     */
    public function init()
    {
        if (null === $this->canRun) {
            $this->canRun = Craft::$app->getUser()->getIsAdmin();
        }

        parent::init();
    }
}
