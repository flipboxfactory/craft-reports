<?php

namespace flipbox\craft\reports\events;

use flipbox\craft\ember\helpers\ObjectHelper;
use flipbox\craft\reports\formats\CSV;
use flipbox\craft\reports\formats\FormatInterface;
use flipbox\craft\reports\formats\JSON;
use flipbox\craft\reports\Reports;
use yii\base\Event;
use yii\base\InvalidConfigException;

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

    /**
     * Register a format using configured report object
     *
     * @param $format
     * @throws InvalidConfigException
     */
    public function add($format)
    {
        if (is_string($format)) {
            $format = ['class' => $format];
        }

        if (is_array($format)) {
            if (array_key_exists('handle', $format)) {
                $this->formats[$format['handle']] = $format;
                return;
            }

            $object = ObjectHelper::create($format, FormatInterface::class);
            $this->formats[$object->getHandle()] = $format;
            return;
        }

        Reports::warning(sprintf(
            "Unable to register format: %s",
            \craft\helpers\Json::encode($format)
        ));
    }
}
