<?php

namespace modava\tiny\components;


class FileManagerPermisstion
{
    public static function setPermissionFileAccess()
    {
        if (!\Yii::$app->user->isGuest) {
            $UserName = \Yii::$app->user->identity->username;

            if (empty($_SESSION['userId'])) {
                $_SESSION['userId'] = $UserName;
            }
            return md5($UserName . 'dsDlFWR9M2xQV');
        }
    }

}