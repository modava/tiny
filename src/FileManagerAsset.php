<?php
namespace modava\tiny;

use yii\web\AssetBundle;

class FileManagerAsset extends AssetBundle
{
    public $sourcePath = '@modava/tiny/assets';

    public $depends = [
        'modava\tiny\TinyMceAsset'
    ];
}
