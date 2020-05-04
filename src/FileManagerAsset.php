<?php
namespace modava\tiny;

use yii\web\AssetBundle;

class FileManagerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/modava/tiny/assets';

    public $js = [
    ];

    public $publishOptions = [
        'forceCopy'=>true,
    ];
}
