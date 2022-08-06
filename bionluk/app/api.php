<?php


class BionlukAPI{

    //POST
    public static $register = "https://bionluk.com/api/users/register/";

    public static $init = "https://bionluk.com/api/users/init/";


    public static function activation_uri($key){
        return "https://bionluk.com/activation?code=$key&u=627f87d4-8d8c-4b60-821f-bfcbd5fd0ea4&utm_term=button&utm_content=button_text&utm_source=email&utm_medium=transactional&utm_campaign=activation";
    }
    
    public static $login = "https://bionluk.com/api/users/login/";

    public static $update_basic_info = "https://bionluk.com/api/users/update_basic_info/";

    public static $messages = "https://bionluk.com/api/chat/getmessages/";

    public static $chat_send = "https://bionluk.com/api/chat/send/";

    public static $chat_archive = "https://bionluk.com/api/chat/change_status/";
    
    public static $ads_all = "https://bionluk.com/api/seller/gig_get_all/";
}