<?php
namespace HeroesofAbenez\Model;

class RecordNotFoundException extends \Exception {
  
}

class AccessDenied extends \Exception {
  
}

class MissingPermissionsException extends AccessDenied {
  
}

class HigherRankException extends AccessDenied {
  
}

class NameInUseException extends \Exception {
  
}

class InvalidStateException extends \RuntimeException {
  
}

class ImmutableException extends InvalidStateException {
  
}
?>