<?php


class BionlukChat{

    private $bionluk;

    public function __construct($bionluk){
        $this->bionluk = $bionluk;
    }
    

    public function getMessages($filter = 'all'){
        if($filter == 'archive') $filter = 'deleted';


        $response = $this->bionluk->http->post(
            BionlukAPI::$messages,
            [
                "filter" => $filter,
                "offset" => "0",
                "limit" => 10,
                "search_term" => ""
            ],
            $this->bionluk->_getHeaderRequired()
        );

        if($response->status() == 200){

            $json = json_decode($response->body(), true);

            if(isset($json['data'])){

                $this->bionluk->log("Mesajlar getirildi : ");

                return $json['data'];

            }else{
                $this->bionluk->log("Mesajlar getirilemedi hata : ". $json['message']);
            }

        }

        $this->bionluk->log("Mesajlar getirilemedi :  error code : ".$response->status());

        return null;

    }

    public function unread(){
        return $this->getMessages('unread');
    }
    public function archive(){
        return $this->getMessages("archive");
    }
    public function send($message,$c_uuid){
        $response = $this->bionluk->http->post(
            BionlukAPI::$chat_send,
            [
                "type" => "message",
                "c_uuid" => $c_uuid,
                "message" => $message,
                "external_id" => null,
                "forceBicoin" => false
            ],
            $this->bionluk->_getHeaderRequired()
        );

        if($response->status() == 200){

            $json = json_decode($response->body(), true);

            if($json['success'] == true){

                $this->bionluk->log("Mesajlar gönderildi:[$c_uuid]  => " . $message);

                return true;

            }else{
                $this->bionluk->log("Mesaj gönderilemedi hata : ". $json['message']);
            }

        }

        $this->bionluk->log("Mesaj gönderilemedi. :  error code : ".$response->status());

        return false;
    }

   
    public function _userarchive($username,$status){

        $response = $this->bionluk->http->post(
            BionlukAPI::$chat_archive,
            [
                "r_username" => $username,
                "status" => $status,
            ],
            $this->bionluk->_getHeaderRequired()
        );

        if($response->status() == 200){

            $json = json_decode($response->body(), true);

            if($json['success'] == true){

                $this->bionluk->log("$username archive taşındı.");

                return true;

            }else{
                $this->bionluk->log("$username archive taşınamadı . Error: ".$json['message']);
            }

        }

        $this->bionluk->log("Arşiv işlemi yapılamadı.:  error code : ".$response->status());

        return false;
    }
    public function userArchive($username){
        return $this->_userarchive($username,0);
    }

    public function userNoArchive($username){
        return $this->_userarchive($username,1);
    }
}