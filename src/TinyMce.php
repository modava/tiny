<?php

namespace modava\tiny;

use modava\tiny\components\FileManagerPermisstion;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class TinyMce extends InputWidget
{
    public $type = 'desc';

    private $upload_path;

    private $clientOptions = [];

    private $clientOptionsFull = [
        'plugins' => [
            "code print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help"
        ],
        'toolbar' => "undo redo | formatselect | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | link image | fullscreen code",
        'content_css' => [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ],
    ];

    public $triggerSaveOnBeforeValidateForm = true;

    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        if (UPLOAD_PATH === 'frontend') {
            $this->upload_path = '../../../../../../frontend/web';
        } else {
            $this->upload_path = '../../../../../../backend/web';
        }
        $this->registerClientScript();
    }

    protected function registerClientScript()
    {
        $view = $this->getView();

        TinyMceAsset::register($view);

        $insFile = FileManagerAsset::register($view);

        $options = $this->setOptions($insFile);


        $view->registerJs(implode("\n", $options));
    }

    protected function setOptions($insFile)
    {
        $this->clientOptionsFull['external_filemanager_path'] = $insFile->baseUrl . '/filemanager/';
        $this->clientOptionsFull['filemanager_title'] = 'Responsive Filemanager';
        $this->clientOptionsFull['external_plugins']['filemanager'] = $insFile->baseUrl . '/filemanager/plugin.min.js';
        $this->clientOptionsFull['external_plugins']['responsivefilemanager'] = $insFile->baseUrl . '/tinymce/plugins/responsivefilemanager/plugin.min.js';
        $this->clientOptionsFull['filemanager_access_key'] = FileManagerPermisstion::setPermissionFileAccess();

        $this->clientOptionsFull['upload_dir'] = '/uploads/filemanager/source/';

        $configPath = [
            'upload_dir' => '/uploads/filemanager/source/',
            'current_path' => $this->upload_path . '/uploads/filemanager/source/',
            'thumbs_base_path' => $this->upload_path . '/uploads/filemanager/thumbs/',
            'base_url' => \Yii::getAlias('@frontendUrl'),
            'upload_path' => $this->upload_path,
        ];

        if (empty($_SESSION['uploadPath'])) {
            $_SESSION['uploadPath'] = urlencode(serialize($configPath));
        }

        $js = [];
        $id = $this->options['id'];

        if ($this->type == 'content') {
            $this->clientOptionsFull['selector'] = "#$id";
            $options = $this->clientOptionsFull;
        } else {
            $this->clientOptions['selector'] = "#$id";
            $options = $this->clientOptions;
        }

        $options = Json::encode($options);

        $js[] = "tinymce.init($options);";
        if ($this->triggerSaveOnBeforeValidateForm) {
            $js[] = "$('#{$id}').parents('form').on('beforeValidate', function() { tinymce.triggerSave(); });";
        }

        return $js;
    }
}
