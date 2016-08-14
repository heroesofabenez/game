<?php
namespace HeroesofAbenez\Model;

class RecordNotFoundException extends \Exception {
  
}

class AccessDenied extends \RuntimeException {
  
}

class MissingPermissionsException extends AccessDenied {
  
}

class HigherRankException extends AccessDenied {
  
}

class NameInUseException extends AccessDenied {
  
}

class InvalidStateException extends \RuntimeException {
  
}

class ImmutableException extends InvalidStateException {
  
}
?>