<?php

require './includes/bootstrap.php';

require './includes/whatsapp/Whatsapp.php';

$request = getAllRequestData();

$whatsapp = new Whatsapp($request);

if($whatsapp->isValidMessage() && !$whatsapp->isFromMe()) { 
    // ....    
}


createJsonFile($whatsapp->getResponse(), 'whatsapp');