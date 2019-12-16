<?php

namespace flipbox\craft\reports\cp;

use Craft;
use flipbox\craft\reports\Reports;
use yii\base\Module;
use yii\web\NotFoundHttpException;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 *
 * @property Reports $module
 */
class Cp extends Module
{
    /**
     * @inheritdoc
     * @throws NotFoundHttpException
     */
    public function beforeAction($action)
    {
        if (!Craft::$app->request->getIsCpRequest()) {
            throw new NotFoundHttpException();
        }

        return parent::beforeAction($action);
    }
}
