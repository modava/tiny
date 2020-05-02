<?php

namespace modava\tiny;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class TinyMce extends InputWidget
{
    public $type = 'desc';
    private $clientOptions = [

    ];

    private $clientOptionsFull = [
        'plugins' => [
            "code print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help"
        ],
        'toolbar' => "undo redo | formatselect | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | link image | code",
        'content_css' => [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ],
//        'external_filemanager_path' => "filemanager/",
//        'filemanager_title' => "Responsive Filemanager",
//        'external_plugins' => [
//            "responsivefilemanager" => "../../tinymce/plugins/responsivefilemanager/plugin.min.js",
//            "filemanager" => "../../filemanager/plugin.min.js"
//        ]
    ];

    public $triggerSaveOnBeforeValidateForm = true;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
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

        $configPath = [
            'upload_dir' => '/uploads/filemanager/source/',
            'current_path' => '../../../../../../frontend/web/uploads/filemanager/source/',
            'thumbs_base_path' => '../../../../../../frontend/web/uploads/filemanager/thumbs/',
            'base_url' => \Yii::getAlias('@frontendUrl'),
        ];
        $this->clientOptionsFull['filemanager_access_key'] = urlencode(serialize($configPath));

        $js = [];
        $id = $this->options['id'];

        $this->clientOptions['selector'] = "#$id";
        $options = $this->clientOptions;

        if ($this->type == 'content') {
            $this->clientOptionsFull['selector'] = "#$id";
            $options = $this->clientOptionsFull;
        }

        $options = Json::encode($options);

        $js[] = "tinymce.init($options);";
        if ($this->triggerSaveOnBeforeValidateForm) {
            $js[] = "$('#{$id}').parents('form').on('beforeValidate', function() { tinymce.triggerSave(); });";
        }

        return $js;
    }
}