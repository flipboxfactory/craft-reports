<?php

namespace flipbox\craft\reports\events;

use flipbox\craft\reports\sources\SourceInterface;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RegisterSources extends Event
{
    /**
     * Event to register sources
     */
    const REGISTER_SOURCES = 'registerSources';

    /**
     * @var SourceInterface[]
     */
    public $sources = [];
}
