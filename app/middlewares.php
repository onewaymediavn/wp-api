<?php

// Client Authentication
function ClientAuthenticator($credentials = null) {    
    if($credentials === null) {
        json([
            'errors' => [[
                    'msg' => '(#) Sorry! You have no permission to access the resources on this server!'
            ]]
        ]);
    }
    
    return true;
}


// Resource Logger
function ResourceLogger() {
    return true;
}

// Resource Counter
function ResourceCounter() {
    return true;
}