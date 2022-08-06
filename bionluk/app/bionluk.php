<?php



class Bionluk extends BionlukMessages{

    public $http;


    public $chat,$ads;
   
    private $devMode = false;

    private $isLogin = false;


    const super_key = "1e291318-f4b6-4a65-8323-a1823dbd7564";

    public $auth = [
        "visitor_id" => "",
        "token" => ""
    ];

    public function __construct(){

        $this->http = new Http();

        $this->chat = new BionlukChat($this);
        $this->ads = new BionlukAds($this);
    }

    private function init(){
        //önce init

        $response = $response = $this->http->post(
            BionlukAPI::$init,
            [
                "deviceToken" => null,
                "appType" => null,
                "appVersion" => null,
                "referer" => "",
                "current_url" => "https://bionluk.com/register_with_email"
            ],
            [
                "super-key: ".self::super_key
            ]
        );

        if($response->status() !== 200){
            $message = "Kayıt işlemi başarısız oldu. visitor-id üretilemedi.";
            $this->log($message);
            return [
                "status" => false,
                "message" => $message
            ];
        }

        return json_decode($response->body(),true);
    }
 
    public function register($data){

        $json = $this->init();
        
        if(empty($json["visitor_id"]) || empty($json["token"])){
            $message = "Kayıt işlemi başarısız oldu. [VISITOR ID , TOKEN getirilemedi.]";
            $this->log($message);
            return [
                "status" => false,
                "message" => $message
            ];
        }

        $visitor_id = $json["visitor_id"];
        $token = $json["token"];

        $response = $this->http->post(
            BionlukAPI::$register,
            [
                "firstname" => $data['firstname'],
                "lastname"  => $data['lastname'] ,
                "username"  => $data['username'],
                "email"  => $data['email'],
                "password"  => $data['password'],
                "redirect_url"  => "/",
                "called_from"  => null,
                "redirect_query"  => "[object Object]"
            ],
            [
                "super-key: ".self::super_key,
                "super-token: ".$token,
                "super-visitor: ".$visitor_id
            ]
        );


        if($response->status() == 500){

            $message = "Kayıt işlemi başarılı";
            $this->log($message);
            return [
                "status" => true,
                "message" => $message
            ];
        }

        if($response->status() == 200){
            $result = json_decode($response->body(),true);
            
            if($result["success"] == true){
                $message = "Kayıt işlemi başarılı";
                $this->log($message);
                return [
                    "status" => true,
                    "message" => $message
                ];
            }else{
                $this->log($result['message']);
                return [
                    "status" => false,
                    "message" => $result['message'],
                    "errors" => $result['data']
                ];

            }
        }   


        $message = "kayıt işlemi başarısız oldu, geri dönen kod:".$response->status();
        $this->log($message);
        return [
            "status" => false,
            "message" => $message
        ];
    }

    public function activation($key){
        return $this->http->get(BionlukAPI::activation_uri($key))->status() == 200;
    }
    public function login($username,$password){

        
        if($this->isCache($username)){

            $this->setProps($this->cache($username));


            return true;

            /*if( $this->_loginControl()){
                
                // eğer geçerli ise
                return true;

            }*/

        }
        

        $json = $this->init();
        
        if(empty($json["visitor_id"]) || empty($json["token"])){
            $message = "Login başarısız oldu. [VISITOR ID , TOKEN getirilemedi.]";
            $this->log($message);
            return false;
        }




        $response = $this->http->post(
            BionlukAPI::$login,
            [
                "email" => $username,
                "password" => $password,
                "redirect_url" => "/",
                "called_from" => null,
            ],
            [
                "super-key: ".self::super_key,
                "super-token: ".$json['token'],
                "super-visitor: ".$json['visitor_id']
            ]
        );

        if($response->status() == 200){

            $data = json_decode($response->body(),true);

            $save['token'] = $data['token'];
            $save['visitor_id'] = $data['visitor_id'];

            $savedata = json_encode($save);
            $this->cacheIt($username,$savedata);

            $this->setProps($savedata);

            $this->log("Giriş yapıldı : " .$username);

            return true;

        }

        $this->log("GİRİŞ BAŞARISIZ OLDU : ".$username);

        return false;
    }

   
    


    public function isLogin(){
        return $this->islogin;
    }
    private function _loginControl(){
        
    }

    public function update_basic_info($firstnamme,$lastname,$title,$brief,$describeYourself){
        $response = $this->http->post(BionlukAPI::$update_basic_info,
        [
            "firstname" => $firstnamme,
            "lastname" => $lastname,
            "title" => $title,
            "brief" => $brief,
            "describeYourself" => $describeYourself
        ],
        $this->_getHeaderRequired()
    );

        if($response->status() == 200){
            
            $json = json_decode($response->body(),true);

            if($json['success']){
                $this->log("Bilgiler güncellendi.");
                return true;
            }

            $this->log("Bilgiler güncellenemedi hata . ". $json['message']);
            return false;
        }

        $this->log("Bilgiler güncellenemedi durum kodu : " . $response->status());

        return false;
    }

    public function _getHeaderRequired(){
        return [
            "super-key: ".self::super_key,
            "super-token: ".$this->auth["token"],
            "super-visitor: ".$this->auth["visitor_id"]
        ];
    }


    

    

    private function setProps($data){
        $json = json_decode($data,true);

        $this->auth["visitor_id"] = $json['visitor_id'];
        $this->auth["token"] = $json['token'];
    }


    private function cacheIt($username,$data){
        $path = md5($username);
        $status =  FileHelper::saveTxt($path,$data); 

        $this->log("Cache kaydedildi :" .$username);

        return $status;
 }
    private function cache($username){
        $path = md5($username);
        return FileHelper::getTxt($path); 
    }
    private function isCache($username){
        $path = md5($username);
        return FileHelper::existsTxt($path);
    }

    function devMode($status){
        $this->devMode = $status;
        return $this;
    }
 
    public function config($config){
        if(isset($config['cookieFolder'])){
            FileHelper::folder($config['cookieFolder']);
        }
        $keys = [
            "devMode" => "devMode"
        ];
        foreach($config as $key => $value){
            if(isset($keys[$key])){
                $this->{$keys[$key]} = $value;
            }
        }
    }
    public function __destruct(){
        if( $this->devMode ){
            print_r($this->getMessages());
        }
    }

}