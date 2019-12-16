<?php

namespace flipbox\craft\reports\cp\controllers\view;

use craft\web\Controller;
use flipbox\craft\ember\helpers\UrlHelper;
use flipbox\craft\reports\Reports;
use flipbox\craft\salesforce\cp\Cp as CpModule;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property CpModule $module
 */
abstract class AbstractController extends Controller
{
    /**
     * The index view template path
     */
    const TEMPLATE_BASE = 'reports/_cp';

    /*******************************************
     * BASE PATHS
     *******************************************/

    /**
     * @return string
     */
    protected function getBaseActionPath(): string
    {
        return Reports::getInstance()->getUniqueId() . '/cp';
    }

    /**
     * @return string
     */
    protected function getBaseCpPath(): string
    {
        return Reports::getInstance()->getUniqueId();
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function getBaseContinueEditingUrl(string $endpoint = ''): string
    {
        return $this->getBaseCpPath() . $endpoint;
    }


    /*******************************************
     * VARIABLES
     *******************************************/

    /**
     * @inheritdoc
     */
    protected function baseVariables(array &$variables = [])
    {
        $module = Reports::getInstance();

        $title = Reports::t("Reports");

        // Settings
        $variables['settings'] = $module->getSettings();
        $variables['title'] = $title;

        // Path to controller actions
        $variables['baseActionPath'] = $this->getBaseActionPath();

        // Path to CP
        $variables['baseCpPath'] = $this->getBaseCpPath();

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = $this->getBaseCpPath();

//        // Select our sub-nav
//        if (!$activeSubNav = Craft::$app->getRequest()->getSegment(2)) {
//            $activeSubNav = 'reports';
//        }
//        $variables['selectedSubnavItem'] = 'reports.' . $activeSubNav;

        // Breadcrumbs
        $variables['crumbs'][] = [
            'label' => $title,
            'url' => UrlHelper::url(Reports::getInstance()->getUniqueId())
        ];
    }
}
