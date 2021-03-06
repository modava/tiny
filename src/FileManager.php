<?php


namespace modava\tiny;

use yii\widgets\InputWidget;
use modava\tiny\components\FileManagerPermisstion;
use yii\helpers\Html;

class FileManager extends InputWidget
{
    public $model;
    public $attribute;
    public $path;
    public $options = [];
    public $label;

    private $upload_path;

    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::hiddenInput($this->name, $this->value, $this->options);
        }

        if (UPLOAD_PATH === 'frontend') {
            $this->upload_path = '../../../../../../frontend/web';
        } else {
            $this->upload_path = '../../../../../../backend/web';
        }

        $view = $this->getView();

        $insFile = FileManagerAsset::register($view);

        $link = $insFile->baseUrl . '/filemanager/';

        $configPath = [
            'upload_dir' => '/uploads/filemanager/source/',
            'current_path' => $this->upload_path . '/uploads/filemanager/source/',
            'thumbs_base_path' => $this->upload_path . '/uploads/filemanager/thumbs/',
            'base_url' => \Yii::getAlias('@frontendUrl'),
            'upload_path' => $this->upload_path,
        ];
        $config = urlencode(serialize($configPath));
        $filemanager_access_key = FileManagerPermisstion::setPermissionFileAccess();
        return $this->render('fileWidget', [
            'link' => $link,
            'filemanager_access_key' => $filemanager_access_key,
            'idName' => $this->options['id'],
            'path' => $this->path,
            'image' => $this->model[$this->attribute],
            'label' => $this->label,
            'config' => $config,
        ]);
    }
}