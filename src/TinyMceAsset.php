<?php
namespace modava\tiny;

use yii\web\AssetBundle;

class TinyMceAsset extends AssetBundle
{
    public $sourcePath = '@vendor/tinymce/tinymce';

    public $js = [
        'tinymce.js'
    ];

    public $publishOptions = [
        'forceCopy'=>true,
    ];
}
