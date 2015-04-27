<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;

use Nette\Database\Context;

class GUser extends Nette\Object implements Nette\Security\IAuthenticator{
  public $id;
  public $user;
  public $role;
  public $name;
  const USER_NOT_FOUND = 1;
  const INVALID_PASSWORD = 2;
  function __construct() { }

  public function authenticate(array $credentials) {
    return $this->login($credentials);
  }

  function login(array $credentials) {
    global $conn;
    $db = new Context($conn);
    $username = $credentials["username"];
    $password = md5($credentials["password"]);
    $row = $db->query("SELECT * FROM users WHERE username='$username'");
    if (!$row OR $row->num_rows == 0) { return self::USER_NOT_FOUND; }
    $row = $row->fetch_object();
    if ($row->password !== $password) { return self::INVALID_PASSWORD; }
    unset($row->password);
    $this->user = $username;
    $this->id = $row->id;
    $_SESSION["uid"] = $this->id;
    $_SESSION["username"] = $this->user;
    $_SESSION["role"] = $row->role;
    $_SESSION["login"] = true;
    $identity = new Identity($row->id, $row->role, $row);
    $_SESSION["identity"] = $identity;
    $db->query("UPDATE users SET lastactivity=CURRENT_TIMESTAMP WHERE id=$this->id");
    return $identity;
  }

  function isLoggedIn() {
    if(isset($_SESSION["login"]) AND $_SESSION["login"]) {
      return true;
    } else { return false; } 
  }

  function logout() {
    $_SESSION["uid"] = false;
    $_SESSION["username"] = false;
    $_SESSION["login"] = false;
    $_SESSION["identity"] = false;
    global $conn;
    $db = new Context($conn);
    $db->query("DELETE FROM logins WHERE user = $this->id AND ip = '{$_SERVER["REMOTE_ADDR"]}'");
    $db->query("UPDATE users SET lastactivity=CURRENT_TIMESTAMP WHERE id=$this->id");
  }

  function getIdentity() {
    return $_SESSION["identity"];
  }
  
  function loginText() {
    global $page;
    global $base_url;
    if($this->isLoggedIn() AND $this->user !== "") {
      $name = $this->user;
      $page->addContent("<div id='logintext'><p>Jste pøihlášen jako $name. Mùžete jít <a href=\"$base_url\">domù</a> nebo <a href=\"$base_url/logout\">se odhlásit</a>.</p></div>\n");
    } else {
      $page->addContent("<div id='logintext'><p><a href=\"$base_url/login\">Pøihlásit se</a></p></div>\n");
    }
  }

  function loginForm() {
    global $club;
    global $base_url;
    $cont = "<div id='content'>\n<form action=\"$base_url/login\" method=\"post\">\n<h1>Pøihlásit se</h1>\nUživatel: <input type=\"text\" name=\"username\"><br>\nHeslo: <input type=\"password\"  name=\"password\"><br>\n<button type=\"submit\" name=\"submit\" value=\"submit\">Pøihlásit se</button>\n</form>\n</div>\n";
    $club->page->addContent($cont);
  }

  function regForm() {
    global $club;
    global $base_url;
    $regForm = $club->lang->translate("Registration Page");
    $usernameField = $club->lang->translate("Username field");
    $passwordField = $club->lang->translate("Password field");
    $realnameInput = $club->lang->translate("Realname input");
    $emailInput = $club->lang->translate("E-mail input");
    $genderSelect = $club->lang->translate("Gender select");
    $genderMale = $club->lang->translate("Gender male option");
    $genderFemale = $club->lang->translate("Gender female option");
    $birthdayInput = $club->lang->translate("Birthday input");
    $regButton = $club->lang->translate("Register button");
    $cont = "<div id='content'>
<form action=\"$base_url/reg\" method=\"post\">
<h1>$regForm</h1>
$usernameField: <input type=\"text\" name=\"username\"><br>
$realnameInput: <input type=\"text\" name=\"realname\"><br>
$passwordField: <input type=\"password\"  name=\"password\"><br>
$emailInput: <input type=\"text\"  name=\"email\"><br>
$genderSelect: <select name=\"gender\">
<option>$genderMale</option>
<option>$genderFemale</option>
</select><br>
$birthdayInput: <input type=\"text\" name=\"birthday\" length=\"12\"><br>
<button type=\"submit\" name=\"submit\" value=\"submit\">$regButton</button>
</form>
</div>\n";
   $club->page->addContent($cont);
  }

  function register() {
    global $conn;
    global $club;
    $db = new Context($conn);
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $sql = "INSERT INTO users (`username`, `password`, `email`, `gender`) VALUES ('$username', MD5('$password'), '$email', $gender);";
    if($sb->query($sql)) {
      $club->page->addContent($club->lang->translate("Registration was successful"));
    }
  }

  function reloadData() {
    if($this->isLoggedIn()) {
      global $conn;
      $db = new Context($conn);
      $row = $db->query("SELECT * FROM users WHERE id='$this->id'");
      $row = $row->fetch_object();
      unset($row->password);
      $_SESSION["username"] = $this->user = $row->username;
      $_SESSION["uid"] = $this->id = $row->id;
      $_SESSION["role"] = $row->role = $row->role;
      $identity = new Identity($row->id, $row->role, $row);
      $_SESSION["identity"] = $identity;
      $db->query("UPDATE users SET lastactivity=CURRENT_TIMESTAMP WHERE id=$this->id");
    }
  }
}
?>