<?php

/***********************************************************************
*    PHP NTLM GET LOGIN 
*    Version 0.2
* ====================================================  
*                                        
* Copyright (c) 2004 Nicolas GOLLET (Nicolas.gollet@secusquad.com)
* Copyright (c) 2004 Flextronics Saint-Etienne
*
* This program is free software. You can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by 
* the Free Software Foundation; either version 2 of the License.         
*
***********************************************************************/

/*
L'identification par NTLM se fait en 6 etape :

etape: | type:      | Info echange
-------|----------------|--------------------------------------------------
    1   | C --> S       | GET ...                               legende : C = Client
-------|----------------|--------------------------------------------------                     S = Serveur
    2   | C <-- S   | 401 Unauthorized
         |              | WWW-Authenticate: NTLM
-------|----------------|--------------------------------------------------
    3   | C --> S       | GET ...
         |              | Authorization: NTLM <base64-encoded type-1-message>
-------|----------------|--------------------------------------------------
    4   | C <-- S   | 401 Unauthorized
         |              | WWW-Authenticate: NTLM <base64-encoded type-2-message>
-------|----------------|--------------------------------------------------
    5   | C --> S       | GET ...
         |              | Authorization: NTLM <base64-encoded type-3-message>
-------|----------------|-------------------------------------------------- 
    6   | C <-- S        | 200 Ok
-------|----------------|--------------------------------------------------
*/



$headers = apache_request_headers();    // Recuperation des l'entetes client
    

if($headers['Authorization'] == NULL){              //si l'entete autorisation est inexistante
    header( "HTTP/1.0 401 Unauthorized" );          //envoi au client le mode d'identification
    header( "WWW-Authenticate: NTLM" );         //dans notre cas le NTLM
    exit;                           //on quitte

};

if(isset($headers['Authorization']))                //dans le cas d'une authorisation (identification)
{       
    if(substr($headers['Authorization'],0,5) == 'NTLM '){   // on verifit que le client soit en NTLM
    
    
        $chaine=$headers['Authorization'];                  
        $chaine=substr($chaine, 5);             // recuperation du base64-encoded type1 message
        $chained64=base64_decode($chaine);      // decodage base64 dans $chained64
        
        if(ord($chained64{8}) == 1){                    
        // |_ byte signifiant l'etape du processus d'identification (etape 3)      
    
        // verification du drapeau NTLM "0xb2" a l'offset 13 dans le message type-1-message :
        if (ord($chained64[13]) != 178){
        echo "Ваш браузер не поддерживает авторизацию по NTLM, используйте пожалуйста IE 7.0 и выше... Спасибо!";
        exit;
        }
            $retAuth = "NTLMSSP";                   
            $retAuth .= chr(0);                  
            $retAuth .= chr(2);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(40);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(1);
            $retAuth .= chr(130);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(2);
            $retAuth .= chr(2);
            $retAuth .= chr(2);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            $retAuth .= chr(0);
            
            $retAuth64 =base64_encode($retAuth);        // кодируем в base64
            $retAuth64 = trim($retAuth64);          // Убираем пробелы в конце и в начале.
            header( "HTTP/1.0 401 Unauthorized" );      // envoi le nouveau header
            header( "WWW-Authenticate: NTLM $retAuth64" );  // avec l'identification supplementaire
            exit;
            
        }

        else if(ord($chained64{8}) == 3){
        //               |_ byte signifiant l'etape du processus d'identification (etape 5)
    
        // Вытаскиваем домен
        
        $lenght_domain = (ord($chained64[31])*256 + ord($chained64[30])); // longueur du domain
        $offset_domain = (ord($chained64[33])*256 + ord($chained64[32])); // position du domain.    
        $domain = substr($chained64, $offset_domain, $lenght_domain); // decoupage du du domain
        
        // Вытаскиваем логин
        $lenght_login = (ord($chained64[39])*256 + ord($chained64[38])); // longueur du login.
        $offset_login = (ord($chained64[41])*256 + ord($chained64[40])); // position du login.
        $login = substr($chained64, $offset_login, $lenght_login); // decoupage du login
        
        // Вытаскиваем пароль  
        $lenght_host = (ord($chained64[47])*256 + ord($chained64[46])); // longueur de l'host.
        $offset_host = (ord($chained64[49])*256 + ord($chained64[48])); // position de l'host.  
        $host = substr($chained64, $offset_host, $lenght_host); // decoupage du l'host  
        
		// добавлено для правильной сверки логина
		$login = strtolower($login)
        

        echo "Domain is  : $domain";
        echo "<br>Login is : $login";
        echo "<br>host is  : $host";
        
        }

    }

}


?>