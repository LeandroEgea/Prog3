<?php
    use \Firebase\JWT\JWT;
  class autentificadorJWT     
  {
    
    function autentificar($string){
        $key = "1234";
        $token = array(
        "iss" => "http://example.org",
        "aud" => "http://example.com",
        "iat" => 1356999524,
        "nbf" => 1357000000
        );

        $jwt = JWT::encode($token, $key);
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        return $decoded;
    }
  }
 ?>