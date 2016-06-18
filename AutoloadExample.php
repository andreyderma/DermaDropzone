<?php

namespace Derma\Dropzone;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * This is just an example.
 */
class AutoloadExample extends \yii\base\Widget
    //InputWidget
    //\yii\base\Widget
{
    public $options = [];

    public $clientEvents = [];
    public $addFiles = [];

    //Default Values
    public $id = 'myDropzone';
    public $uploadUrl = '/site/upload';
    public $dropzoneContainer = 'myDropzone';
    public $previewsContainer = 'previews';
    public $autoDiscover = false;
    public $renameFile = "";


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
//        $imageData = isset($this->options['addFiles']) ? $this->options['addFiles'] : "";
//        unset($this->options['addFiles']);
        //$js = 'Dropzone.autoDiscover = ' . $this->autoDiscover . '; var ' . $this->id . ' = new Dropzone("div#' . $this->dropzoneContainer . '", ' . Json::encode($this->options) . ');';
        $js = 'Dropzone.autoDiscover = ' . $this->autoDiscover . ';';

        $inputJson = $this->options;
        $renameFilename = $inputJson['renameFilename'];
        unset($inputJson['renameFilename']);

        $input = Json::encode($inputJson);

        $js .= 'var ' . $this->id . ' = new Dropzone("div#' . $this->dropzoneContainer . '", ' . $input . ');';
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js .= "$this->id.on('$event', $handler);";
            }
        }

        if(isset($this->options['renameFilename'])){
            $js .= $this->dropzoneContainer.".options.renameFilename = ".$renameFilename.";";
        }

        $js .= $this->setFiles($this->addFiles);

        $view->registerJs($js);
        DermaAsset::register($view);
    }

    protected function setFiles($files = [])
    {
        $js = "";
        if (empty($files) === false) {
            foreach ($files as $key=>$vls)
            {
                $name = $vls['name'];
                $fileUrl = $vls['fileUrl'];
                unset($vls['fileUrl']);
                $js .=  "var mockFile_".$key." = ".Json::encode($vls).";";
                //$js .=  "var mockFile_".$key." = { name: \"".$name."\"};";
                $js .= $this->dropzoneContainer.".options.addedfile.call(".$this->dropzoneContainer.", mockFile_".$key.");";
                $js .= $this->dropzoneContainer.".options.thumbnail.call(".$this->dropzoneContainer.", mockFile_".$key.", \"".$fileUrl."\");";
                $js .= $this->dropzoneContainer.".options.complete.call(".$this->dropzoneContainer.", mockFile_".$key.", \"".$name."\");";
            }
        }

        return $js;
    }
}
