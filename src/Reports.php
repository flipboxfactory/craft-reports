<?php

namespace flipbox\craft\reports;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use flipbox\craft\ember\modules\LoggerTrait;
use flipbox\craft\reports\models\Settings as SettingsModel;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @method SettingsModel getSettings()
 *
 * @property-read services\Formats $formats
 * @property-read services\Reports $reports
 */
class Reports extends Plugin
{
    use LoggerTrait;

    /**
     * @var string
     */
    public static $category = 'reports';

    public $controllerMap = [
        'users' => [
           'class' => 'app\controllers\PostController',
           'pageTitle' => 'something new',
        ]
      ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Components
        $this->setComponents([
            'reports' => services\Reports::class,
            'formats' => services\Formats::class
        ]);

        // Modules
        $this->setModules([
            'cp' => cp\Cp::class
        ]);

        // CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            [self::class, 'registerCpUrlRules']
        );
    }


    /*******************************************
     * SETTINGS
     *******************************************/

    /**
     * @inheritdoc
     * @return SettingsModel
     */
    public function createSettingsModel()
    {
        return new SettingsModel();
    }


    /*******************************************
     * SERVICES
     *******************************************/

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @return services\Reports
     */
    public function getReports(): services\Reports
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('reports');
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @return services\Formats
     */
    public function getFormats(): services\Formats
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->get('formats');
    }

    /*******************************************
     * MODULES
     *******************************************/

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @return cp\Cp
     */
    public function getCp(): cp\Cp
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getModule('cp');
    }

    /*******************************************
     * TRANSLATE
     *******************************************/

    /**
     * Translates a message to the specified language.
     *
     * This is a shortcut method of [[\Craft::t()]].
     *     *
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
     * [[\yii\base\Application::language|application language]] will be used.
     * @return string the translated message.
     */
    public static function t($message, $params = [], $language = null)
    {
        return Craft::t(self::$category, $message, $params, $language);
    }

    /*******************************************
     * EVENTS
     *******************************************/

    /**
     * @param RegisterUrlRulesEvent $event
     */
    public static function registerCpUrlRules(RegisterUrlRulesEvent $event)
    {
        $event->rules = array_merge(
            $event->rules,
            [
                'reports' => 'reports/cp/view/reports/index',
                'reports/<identifier:(.*)>' => 'reports/cp/view/reports/view',
            ]
        );
    }
}
