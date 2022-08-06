<?php


require 'bionluk/init.php';



$bionluk = new Bionluk();


$data['firstname'] = "yenibirhesap";
$data['lastname'] ="test";
$data['username']= "myusernamebs23x";
$data['email'] = "readonlyworld2x12@gmail.com";
$data['password'] = "test123";


//$bionluk->register($data);


$key = "";
$bionluk->activation($key);

$data['email'] = "test@test.com";
$data['password'] = "test";

$bionluk->login($data['email'],$data['password']);



//$bionluk->update_basic_info("ahmet","bilardo","Burası başlık","brief","açıklama");

// CHAT İŞLEMLERİ

/*$messages = $bionluk->chat->getMessages();



$firstChat = $messages["conversations"][0];

$firstChat_id = $firstChat["c_uuid"];


$bionluk->chat->send("hello world",$firstChat_id);

*/

//all messages
$messages = $bionluk->chat->getMessages();


// UNREAD MESSAGES
$messages = $bionluk->chat->getMessages("unread");
//alternative
$messages = $bionluk->chat->unread();

// ARCHIVE MESSAGES
$messages = $bionluk->chat->getMessages("archive");
//alternative
$archive = $bionluk->chat->archive();


$username = "test";
// user chat set archive
$bionluk->chat->userArchive($username);
// user chat set no archive
$bionluk->chat->userNoArchive($username);



//İLAN İŞLEMLERİ

// tüm ilanlarımı getir
$bionluk->ads->getAll();

//aktif ilanları getir
$bionluk->ads->active();

//pasif ilanları getir
$bionluk->ads->passive();

// tamamlanması devam eden ilanları
$bionluk->ads->continue();

// onaylanmayan ilanları göster
$bionluk->ads->notConfirmed();