<?php

namespace flipbox\craft\reports\controllers;

use Craft;
use craft\web\Controller;
use flipbox\craft\reports\actions\Run;

/**
 * This controller runs a report.  It is not intended to handle any other operation.
 *
 * We accept all 'action' id's (which is actually the report handle) and route them through the same action.
 *
 * Ex: reports/run/users -> runs a report with 'users' as the handle
 * Ex: reports/run/entries -> runs a report with 'entries' as the handle
 */
class RunController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => Run::class
            ]
        ];
    }

    /**
     * Add the report 'identifier' to params.
     *
     * @inheritDoc
     */
    public function runAction($id, $params = [])
    {
        $params['identifier'] = $id;
        return parent::runAction($id, $params);
    }

    /**
     * @param string $id
     * @return object|\yii\base\Action|null
     * @throws \yii\base\InvalidConfigException
     */
    public function createAction($id)
    {
        return Craft::createObject([
            'class' => Run::class
        ], ['index', $this]);
    }
}
