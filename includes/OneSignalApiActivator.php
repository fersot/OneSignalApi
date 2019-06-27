<?php
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class OneSignalApiActivator
{

    public static function activate()
    {
        self::addoptions();
    }

    static function addoptions()
    {
        OneSignalApi::AddOption('onesignal_app_id','');
        OneSignalApi::AddOption('onesignal_api_key','');
    }
}
