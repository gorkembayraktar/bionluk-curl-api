<?php

$requireList  = ["helper.file","helper.http","helper.response" ,"app.messages","app.api","app.chat","app.ads",
"app.bionluk",
];
foreach($requireList as $require) require str_replace('.',"/",$require).".php";
FileHelper::folder(__DIR__.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR);

define("MAIN_DIRECTORY",__DIR__);