<?php

namespace flipbox\craft\reports\events;

use flipbox\craft\reports\formats\CSV;
use flipbox\craft\reports\formats\FormatInterface;
use flipbox\craft\reports\formats\JSON;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RegisterFormats extends Event
{
    /**
     * Event to register formats
     */
    const REGISTER_FORMATS = 'registerFormats';

    /**
     * @var FormatInterface[]
     */
    public $formats = [
        'json' => [
            'class' => JSON::class
        ],
        'csv' => [
            'class' => CSV::class
        ]
    ];
}
