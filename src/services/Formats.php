<?php

namespace flipbox\craft\reports\services;

use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use flipbox\craft\ember\helpers\ObjectHelper;
use flipbox\craft\reports\events\RegisterFormats;
use flipbox\craft\reports\formats\FormatInterface;
use flipbox\craft\reports\helpers\SettingsHelper;
use flipbox\craft\reports\Reports;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class Formats extends Component
{
    /**
     * @var array|null
     */
    private $overrides = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $event = new RegisterFormats();

        RegisterFormats::trigger(
            Reports::class,
            $event::REGISTER_FORMATS,
            $event
        );

        $this->overrides = $event->formats;
    }

    /**
     * @return FormatInterface[]
     * @throws InvalidConfigException
     */
    public function all(): array
    {
        // Todo - add database storage
        $configs = [];

        // Get all overrides
        $overrides = $this->overrides;

        // The return results
        $results = [];

        foreach ($configs as $config) {
            if (null !== ($result = $this->createFromConfig($config))) {
                ArrayHelper::remove($overrides, $result->getHandle());
                $results[] = $result;
            }
        }

        foreach ($overrides as $override) {
            if (null !== ($result = $this->createFromConfig($override))) {
                $results[] = $result;
            }
        }

        return $results;
    }

    /**
     * @param $format
     * @return FormatInterface|null
     */
    public function find($format)
    {
        if ($format instanceof FormatInterface) {
            return $format;
        }

        if (is_string($format)) {
            return $this->findByHandle($format);
        }

        return null;
    }

    /**
     * @param $format
     * @return FormatInterface
     * @throws Exception
     */
    public function get($format)
    {
        if (null === ($object = $this->find($format))) {
            throw new Exception(
                sprintf(
                    "Could not find the format %s",
                    $format
                )
            );
        }

        return $object;
    }

    /**
     * @param string $format
     * @return mixed|null
     */
    public function findByHandle(string $format)
    {
        // Todo - add database storage
        $config = ['handle' => $format];
        return $this->createFromConfig($config);
    }


    /**
     * @param array $config
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function create(array $config): FormatInterface
    {
        $config = $this->prepareConfig($config);

        // Provider class
        $class = ObjectHelper::checkConfig(
            $config,
            FormatInterface::class
        );

        return new $class($config);
    }

    /**
     * @param array $config
     * @return FormatInterface|null
     */
    private function createFromConfig(array $config)
    {
        try {
            return $this->create($config);
        } catch (InvalidConfigException $e) {
            Reports::error(
                sprintf(
                    "Exception caught while trying to create report format: [%s]. Exception: [%s].",
                    Json::encode($config),
                    (string)Json::encode([
                        'Trace' => $e->getTraceAsString(),
                        'File' => $e->getFile(),
                        'Line' => $e->getLine(),
                        'Code' => $e->getCode(),
                        'Message' => $e->getMessage()
                    ])
                ),
                __METHOD__
            );
        }

        return null;
    }

    /**
     * @param array $config
     * @return array
     */
    protected function prepareConfig(array $config = []): array
    {
        // Merge in settings
        $config = array_merge($config, SettingsHelper::extract($config));

        // Apply override settings
        if (null !== ($handle = $config['handle'] ?? null)) {
            $config = array_merge(
                $config,
                $this->getOverride($handle)
            );
        }

        return $config;
    }

    /**
     * Get a type override configuration by handle
     *
     * @param string $handle
     * @return array
     */
    private function getOverride(string $handle): array
    {
        return $this->overrides[$handle] ?? [];
    }
}
