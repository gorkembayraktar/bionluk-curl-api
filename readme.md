
## Bionluk API V1

> Bu api ' yı kullanabilmek için projenize dahil etmeniz gerekir.
> `require 'bionluk/init.php';`


### API INIT İşlemi

    $bionluk = new Bionluk();

### HESAP OLUŞTUR

        $data['firstname'] = "new_account_name";
        $data['lastname'] ="new_account_lastname";
        $data['username']= "new_username";
        $data['email'] = "new_mail_adress";
        $data['password'] = "new_password";

        $bionluk->register($data);

### HESAP AKTİVASYONU

        $key = "";
        $bionluk->activation($key);


### Giriş Bilgileri

      $data['email'] = "mail_adress";
      $data['password'] = "password";
    
  ### Giriş Yapmak
  

> Giriş bilgileri cache altında olduğunu unutmayınız.

    // return @boolean
    $login = $bionluk->login($data['email'],$data['password']);


### Kullanıcı İşlemleri
Giriş yapıldıktan sonra verileri okuyabiliriz.
#### Kullanıcı Bilgilerini Güncelle

    $bionluk->update_basic_info("ahmet","fidan","Burası başlık","brief","açıklama");



### Chat işlemleri
	Kullanıcılara mesaj gönderebilir, mesajları listeyelebilir, kullanıcıları chatte arşivleyebilirsiniz.

#### Tüm Mesajları Getir

    $messages = $bionluk->chat->getMessages();

#### Okunmamış Mesajları Getir
    // UNREAD MESSAGES
    $messages = $bionluk->chat->getMessages("unread");
    //alternative
    $messages = $bionluk->chat->unread(); 


#### Arşivlenmiş Mesajları Getir
    // ARCHIVE MESSAGES
    $messages = $bionluk->chat->getMessages("archive");
    //alternative
    $archive = $bionluk->chat->archive();

#### Kullanıcıya Mesaj Gönder
     // Okunmamış mesajlardan ilk mesaja mesaj gönderir.
     $messages = $bionluk->chat->getMessages('unread');
     $firstChat = $messages["conversations"][0];
     $firstChat_id = $firstChat["c_uuid"];
     $message = "hello world";
     $bionluk->chat->send($message,$firstChat_id);	

### Kullancıyı Arşive Ekle

   $username = "test";
    // user chat set archive
    $bionluk->chat->userArchive($username); 

### Kullancıyı Arşivden Çıkart
    // user chat set no archive
    $bionluk->chat->userNoArchive($username);


### İlan İşlemleri
Kullanıcının ilanlarıyla ilgili işlemler.

#### Tüm ilanlarımı  getir
    // tüm ilanlarımı getir
    $bionluk->ads->getAll();

#### Tüm aktif ilanlarımı  getir
    //aktif ilanları getir
    $bionluk->ads->active();

#### Tüm pasif ilanlarımı  getir
    //aktif ilanları getir
    $bionluk->ads->passive();

    
#### tamamlanması devam eden ilanlarımı  getir
    //aktif ilanları getir
    $bionluk->ads->continue();

#### onaylanmayan ilanlarımı getir
    //aktif ilanları getir
    $bionluk->ads->notConfirmed();



## Örnek: Okunamamış tüm mesajlara cevap yaz
     // Okunmamış mesajlardan ilk mesaja mesaj gönderir.
     $messages = $bionluk->chat->getMessages('unread');
     foreach($messages["conversations"] as $conversation){
     	$conersation_id = $conversation["c_uuid"];
     	$message = "hello world";
     	$bionluk->chat->send($message,$conersation_id);
     }
    		


Aksiyonlar burada sona eriyor, daha fazlası için projeyi yıldızlamayı unutmayın.


   
