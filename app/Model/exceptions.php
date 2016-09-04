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


class OpponentNotFoundException extends RecordNotFoundException {
  
}

class ItemNotFoundException extends RecordNotFoundException {
  
}

class ItemNotOwnedException extends AccessDenied {
  
}

class ItemAlreadyEquippedException extends InvalidStateException {
  
}

class ItemNotWornException extends InvalidStateException {
  
}

class GuildNotFoundException extends RecordNotFoundException {
  
}

class PlayerNotFoundException extends RecordNotFoundException {
  
}

class PlayerNotInGuild extends AccessDenied {
  
}

class NotInGuildException extends AccessDenied {
  
}

class CannotPromoteHigherRanksException extends HigherRankException {
  
}

class CannotDemoteHigherRanksException extends HigherRankException {
  
}

class CannotKickHigherRanksException extends HigherRankException {
  
}

class CannotPromoteToGrandmaster extends AccessDenied {
  
}

class CannotDemoteLowestRankException extends AccessDenied {
  
}

class CannotHaveMoreDeputies extends AccessDenied {
  
}

class GrandmasterCannotLeaveGuildException extends AccessDenied {
  
}

class StageNotFoundException extends RecordNotFoundException {
  
}

class CannotTravelToStageException extends AccessDenied {
  
}

class NotEnoughExperiencesException extends AccessDenied {
  
}

class InvalidStatException extends \OutOfBoundsException {
  
}

class NoStatPointsAvailableException extends AccessDenied {
  
}

class CannotSeeRequestException extends AccessDenied {
  
}

class CannotAcceptRequestException extends AccessDenied {
  
}

class CannotDeclineRequestException extends AccessDenied {
  
}

class RequestAlreadyHandledException extends InvalidStateException {
  
}

class RequestNotFoundException extends RecordNotFoundException {
  
}

class InvalidSkillTypeException extends \OutOfBoundsException {
  
}

class SkillNotFoundException extends RecordNotFoundException {
  
}

class NoSkillPointsAvailableException extends AccessDenied {
  
}

class SkillMaxLevelReachedException extends AccessDenied {
  
}

class CannotLearnSkillException extends AccessDenied {
  
}

class PetNotFoundException extends RecordNotFoundException {
  
}

class PetNotOwnedException extends AccessDenied {
  
}

class PetNotDeployedException extends InvalidStateException {
  
}

class PetAlreadyDeployedException extends InvalidStateException {
  
}

class CommandNotFoundException extends \OutOfBoundsException {
  
}

class CommandNameAlreadyUsedException extends InvalidStateException {
  
}
?>