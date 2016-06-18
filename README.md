Yii2 dropzone extension 
========================
Yii2 dropzone extension 

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist hamada/yii2-dermadropzone "*"
```

or add

```
"hamada/yii2-dermadropzone": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \Derma\Dropzone\AutoloadExample::widget(); ?> 
```


More detail options :  [dropzonejs official docs](http://www.dropzonejs.com/#toc_6)
```php
<?=  \Derma\Dropzone\AutoloadExample::widget([
        'options' => [
            'addRemoveLinks' => true,
            'url'=>Yii::$app->getUrlManager()->createUrl(['tempupload/upload']),
            'acceptedFiles'=> "image/jpeg,image/png",
            'dictDefaultMessage'=>'Pilih gambar atau tarik gambar ke sini. ',
        ],
        'clientEvents' => [
            'success'=> "function(file,response){
                console.log(file);
            }",
            'removedfile' => "function(file){
                console.log(file);
            }"
        ],
    ]);
?>
```

Add existing images:
```php
<?php  
    $arr = []; //store in array
    foreach ($modelImages as $values)
    {
        $arr[] = array(
            'name'=>$values->filename,
            'fileUrl'=>'YOUR_FILE_URL'
        );
        /*
            Example amazon s3 url
            https://s3-ap-southeast-1.amazonaws.com/bucket/donald_trump_sucks.jpg
        */
    }
    
    echo \Derma\Dropzone\AutoloadExample::widget([
            'addFiles'=>$arr, //put your array here
            'options' => [
                'addRemoveLinks' => true,
                'url'=>Yii::$app->getUrlManager()->createUrl(['tempupload/upload']),
                'acceptedFiles'=> "image/jpeg,image/png",
                'dictDefaultMessage'=>'Pilih gambar atau tarik gambar ke sini. ',
            ],
            'clientEvents' => [
                'success'=> "function(file,response){
                    console.log(file);
                }",
                'removedfile' => "function(file){
                    console.log(file);
                }"
            ],
        ]);
?>
```

Example of upload method :

```php
public function actionUpload()
{
    $fileName = 'file';
    $uploadPath = './files';

    if (isset($_FILES[$fileName])) {
        $file = \yii\web\UploadedFile::getInstanceByName($fileName);
        if ($file->saveAs($uploadPath . '/' . $file->name)) {
            //Save to database here
            echo \yii\helpers\Json::encode($file);
        }
    }

    return false;
}
```