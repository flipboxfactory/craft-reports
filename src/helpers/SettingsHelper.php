<?php

namespace flipbox\craft\reports\helpers;

use craft\helpers\Json;
use flipbox\craft\ember\helpers\ArrayHelper;

class SettingsHelper
{
    /**
     * @param array $config
     * @return array
     */
    public static function extract(array &$config): array
    {
        // We could init the SettingsInterface and pass them through there if needed
        $settings = ArrayHelper::remove($config, 'settings', []);

        if (is_string($settings)) {
            $settings = Json::decodeIfJson($settings);
        }

        if (!is_array($settings)) {
            $settings = [$settings];
        }

        return $settings;
    }
}