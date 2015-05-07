<?php
use Nette\Security as NS;

class Authenticator extends Nette\Object implements NS\IAuthenticator {
  function __construct() { }
  
  function authenticate(array $credentials) {
    $dev_servers = array("localhost", "kobliha", "test.heroesofabenez.tk");
    if(in_array($_SERVER["SERVER_NAME"], $dev_servers)) {
      return new NS\Identity(1, "admin");
    } else { }
  }
}
?>