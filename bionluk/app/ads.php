<?php


class BionlukAds{

    private $bionluk;

    public function __construct($bionluk){
        $this->bionluk = $bionluk;
    }
    

    public function getAll(){
        return $this->_getAds(null);
    }
    public function active(){
        return $this->_getAds(1);
    }
    public function passive(){
        return $this->_getAds(2);
    }
    public function notConfirmed(){
        return $this->_getAds(-2);
    }
    public function continue(){
        return $this->_getAds(0);
    }
    public function _getAds($status){

        $response = $this->bionluk->http->post(
            BionlukAPI::$ads_all,
            [
                "username" => null,
                "offset" => "0",
                "limit" => 15,
                "status" =>$status,
                "preventRedirect" => null
            ],
            $this->bionluk->_getHeaderRequired()
        );

        if($response->status() == 200){

            $json = json_decode($response->body(), true);

            if(isset($json['data'])){

                $this->bionluk->log("İlanlar getirildi. : ");

                return $json['data']['gigs'];

            }else{
                $this->bionluk->log("İlanlar getirilemedi : ". $json['message']);
            }

        }

        $this->bionluk->log("İlanlar getirilemedi :  error code : ".$response->status());

        return null;

    }

    
}