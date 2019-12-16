<?php

namespace flipbox\craft\reports\web\assets\dataTables;

use yii\web\AssetBundle;

/**
 * Application asset bundle.
 */
class DataTables extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/dist';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->js = [
            'js/datatables.min.js'
        ];

        $this->css = [
            'css/datatables.min.css'
        ];

        parent::init();
    }
}
