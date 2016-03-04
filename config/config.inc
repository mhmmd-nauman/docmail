<?php

//DB details
define('HOSTNAME_DB',"");
define('DATABASE_DB',"");
define('USERNAME_DB',"");
define('PASSWORD_DB',"");

define('ACTIVE_USER_ID', '1');

DEFINE('BASE_URL', "");
DEFINE('WSDL_URL', "");

ini_set('max_execution_time', 0);

$type_arr = array(
    'A4Letter' => 'A4 Letter Single Sided',
    'A4LetterDouble' => 'A4 Letter Double Sided',
    'GreetingCardA5' => 'Greeting Card A5',
    'PostcardA5' => 'Postcard A5',
    'PostcardA5Right' => 'Postcard A5 Right',
    'PostcardA6' => 'Postcard A6',
    'PostcardA6Right' => 'Postcard A6 Right',
);

$prod_type_arr = array(
    'A4Letter' => 'A4Letter',
    'A4LetterDouble' => 'A4Letter',
//    'A3FoldedSheet' => '',
    'GreetingCardA5' => 'GreetingCard',
    'PostcardA5' => 'Postcard',
    'PostcardA5Right' => 'Postcard',
    'PostcardA6' => 'Postcard',
    'PostcardA6Right' => 'Postcard',
);

$postage_arr = array(
  'Standard' => 'Standard Postage',  
  'First' => '1st Class Postage',  
);
