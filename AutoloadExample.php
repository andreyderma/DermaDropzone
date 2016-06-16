<?php

namespace Derma\Dropzone;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * This is just an example.
 */
class AutoloadExample extends InputWidget
    //\yii\base\Widget
{
    public $options = [];

    public $clientEvents = [];

    //Default Values
    public $id = 'myDropzone';
    public $uploadUrl = '/site/upload';
    public $dropzoneContainer = 'myDropzone';
    public $previewsContainer = 'previews';
    public $autoDiscover = false;


    public function init()
    {
        parent::init();

        if (!isset($this->options['url'])) $this->options['url'] = $this->uploadUrl; // Set the url
        if (!isset($this->options['previewsContainer'])) $this->options['previewsContainer'] = '#' . $this->previewsContainer; // Define the element that should be used as click trigger to select files.
        if (!isset($this->options['clickable'])) $this->options['clickable'] = true; // Define the element that should be used as click trigger to select files.
        $this->autoDiscover = $this->autoDiscover===false?'false':'true';

        if(\Yii::$app->getRequest()->enableCsrfValidation){
            $this->options['headers'][\yii\web\Request::CSRF_HEADER] = \Yii::$app->getRequest()->getCsrfToken();
            $this->options['params'][\Yii::$app->getRequest()->csrfParam] = \Yii::$app->getRequest()->getCsrfToken();
        }

        \Yii::setAlias('@dropzone', dirname(__FILE__));
        $this->registerAssets();
    }

    public function run()
    {
        return Html::tag('div', $this->renderDropzone(), ['id' => $this->dropzoneContainer, 'class' => 'dropzone']);
    }

    private function renderDropzone()
    {
        $data = Html::tag('div', '', ['id' => $this->previewsContainer,'class' => 'dropzone-previews']);

        return $data;
    }

    /**
     * Registers assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        $imageUrl = isset($this->options['imageUrl']) ? $this->options['imageUrl'] : "";
        unset($this->options['imageUrl']);
        $js = 'Dropzone.autoDiscover = ' . $this->autoDiscover . '; var ' . $this->id . ' = new Dropzone("div#' . $this->dropzoneContainer . '", ' . Json::encode($this->options) . ');';

        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js .= "$this->id.on('$event', $handler);";
            }
        }

        if(!$this->model->isNewRecord)
        {
            foreach ($this->model as $key=>$vls)
            {
                $fileName = $this->name;
                $js .=  "var mockFile_".$key." = { name: \"".$vls->$fileName."\"};";
                $js .= "myDropzone.options.addedfile.call(myDropzone, mockFile_".$key.");";
                $js .= "myDropzone.options.thumbnail.call(myDropzone, mockFile_".$key.", \"".$imageUrl."/".$vls->$fileName."\");";
            }
        }

        $view->registerJs($js);
        DermaAsset::register($view);
    }
}
