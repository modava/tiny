<?php

namespace modava\tiny\components;


class FileManagerPermisstion
{
    public static function setPermissionFileAccess()
    {
        if (!\Yii::$app->user->isGuest) {
            $UserName = \Yii::$app->user->identity->username;

            $akey = md5($UserName . 'dsDlFWR9M2xQV');

            \Yii::$app->session->set('userId', $UserName);
            return $akey;
        }
    }

}