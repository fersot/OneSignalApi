<?php

class OneSignalApi
{
    /**
     * Constant Api URL
     */
    const ApiNotification = "https://onesignal.com/api/v1/notifications";

    /**
     * Send a Notification Push By Segment
     * @param $segments
     * @param $appid
     * @param $apikey
     * @param null $post
     * @return bool|string
     */
    public static function SendMessageBySegment($segments, $appid, $apikey, $post = null)
    {
        $content = array(
            "en" => 'In Develop message'
        );
        $fields = array(
            'app_id' => $appid,
            'included_segments' => $segments,
            'contents' => $content
        );
        $fields = json_encode($fields);
        return self::Curl($fields, $apikey, self::ApiNotification);
    }

    /**
     * Send a Notification Push By Language
     * @param $language
     * @param $appid
     * @param $apikey
     * @param null $post
     * @return mixed
     */
    public static function SendMessageByLanguage($language, $appid, $apikey, $post = null)
    {
        $content = array(
            "en" => 'In Develop message'
        );
        $fields = array(
            'app_id' => $appid,
            'filters' => [
                [
                    "field" => "language",
                    "relation" => "=",
                    "value" => $language]
            ],
            'contents' => $content
        );
        $fields = json_encode($fields);
        return Curl($fields, $apikey, self::ApiNotification);
    }

    /**
     * Curl to send Notification
     * @param $fields
     * @param $apikey
     * @param $ApiNotification
     * @return bool|string
     */
    static function Curl($fields, $apikey, $ApiNotification)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ApiNotification);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $apikey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * Add a option
     * @param $key
     * @param $value
     */
    public static function AddOption($key, $value)
    {
        add_option($key, $value);
    }

    /**
     * Get a Option
     * @param $key
     * @return string
     */
    public static function GetOption($key){
      return get_option($key);
    }

    /**
     * Update a Option
     * @param $key
     * @param $value
     */
    public static function UpdateOption($key, $value)
    {
        update_option($key, $value);
    }

    /**
     * Remove a Option
     * @param $key
     */
    public static function DeleteOption($key){
        delete_option($key);
    }
}

