<?php

namespace flipbox\craft\reports\services;

use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use flipbox\craft\ember\helpers\ObjectHelper;
use flipbox\craft\reports\events\RegisterReports;
use flipbox\craft\reports\helpers\SettingsHelper;
use flipbox\craft\reports\Reports as Plugin;
use flipbox\craft\reports\reports\Report;
use flipbox\craft\reports\reports\ReportInterface;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class Reports extends Component
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

        $event = new RegisterReports();

        RegisterReports::trigger(
            Plugin::class,
            RegisterReports::REGISTER_REPORTS,
            $event
        );

        $this->overrides = $event->reports;
    }

    /**
     * @return ReportInterface[]
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
     * @param $handle
     * @return ReportInterface|null
     */
    public function find($handle)
    {
        if ($handle instanceof ReportInterface) {
            return $handle;
        }

        if (is_string($handle)) {
            return $this->findByHandle($handle);
        }

        return null;
    }

    /**
     * @param $handle
     * @return ReportInterface
     * @throws Exception
     */
    public function get($handle)
    {
        if (null === ($object = $this->find($handle))) {
            throw new Exception(
                sprintf(
                    "Could not find the report %s",
                    $handle
                )
            );
        }

        return $object;
    }

    /**
     * @param string $handle
     * @return ReportInterface|null
     */
    protected function findByHandle(string $handle)
    {
        // Todo - add database storage
        $config = ['handle' => $handle];
        return $this->createFromConfig($config);
    }

    /**
     * @param array $config
     * @return ReportInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function create(array $config): ReportInterface
    {
        $config = $this->prepareConfig($config);

        // Provider class
        return ObjectHelper::create(
            $config,
            ReportInterface::class
        );
    }

    /**
     * Find a class from a config
     *
     * @param $config
     * @param bool $removeClass
     * @return null|string
     */
    protected function findClassFromConfig(&$config)
    {
        // Normalize the config
        if (is_string($config)) {
            // Set as class
            $class = $config;

            // Clear class from config
            $config = '';
        } elseif (is_object($config)) {
            return get_class($config);
        } else {
            // Force Array
            if (!is_array($config)) {
                $config = ArrayHelper::toArray($config, [], false);
            }

            $class = ArrayHelper::getValue(
                $config,
                'class'
            );
        }

        return $class;
    }

    /**
     * @param array $config
     * @return ReportInterface|null
     */
    private function createFromConfig(array $config)
    {
        try {
            return $this->create($config);
        } catch (InvalidConfigException $e) {
            Plugin::error(
                sprintf(
                    "Exception caught while trying to create report: [%s]. Exception: [%s].",
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

            throw $e;
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
