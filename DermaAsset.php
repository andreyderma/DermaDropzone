<?php
namespace Derma\Dropzone;
use yii\web\AssetBundle;

/**
 * Created by PhpStorm.
 * User: andreyderma
 * Date: 6/16/16
 * Time: 12:52 AM
 */
class DermaAsset extends AssetBundle
{

    public $sourcePath = '@vendor/hamada/yii2-dermadropzone/vendor/bower';

    public $js = [
        "dropzone/dist/min/dropzone.min.js"
    ];

    public $css = [
        "dropzone/dist/min/dropzone.min.css"
    ];

    /**
     * @var array
     */
    public $publishOptions = [
        'forceCopy' => true
    ];

}