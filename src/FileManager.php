<?php


namespace modava\tiny;


use kartik\base\InputWidget;
use modava\tiny\components\FileManagerPermisstion;
use yii\base\Widget;
use yii\helpers\Html;

class FileManager extends InputWidget
{
    public $model;
    public $attribute;
    public $options = [];
    public $label;

    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::hiddenInput($this->name, $this->value, $this->options);
        }

        $view = $this->getView();

        $insFile = FileManagerAsset::register($view);

        $link = $insFile->baseUrl . '/filemanager/';

        $configPath = [
            'upload_dir' => '/uploads/filemanager/source/',
            'current_path' => '../../../../../../frontend/web/uploads/filemanager/source/',
            'thumbs_base_path' => '../../../../../../frontend/web/uploads/filemanager/thumbs/',
            'base_url' => \Yii::getAlias('@frontendUrl'),
            'FileManagerPermisstion' => FileManagerPermisstion::setPermissionFileAccess()
        ];
        $filemanager_access_key = urlencode(serialize($configPath));
        return $this->render('fileWidget', [
            'link' => $link,
            'filemanager_access_key' => $filemanager_access_key,
            'idName' => $this->options['id'],
            'image' => $this->model->image,
            'label' => $this->label,
        ]);
    }
}