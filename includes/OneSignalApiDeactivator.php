<?php
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class OneSignalApiDeactivator
{

    public static function deactivate()
    {
        self::removeoptions();
    }

    static function removeoptions()
    {
        OneSignalApi::DeleteOption('onesignal_app_id');
        OneSignalApi::DeleteOption('onesignal_api_key');
    }
}
