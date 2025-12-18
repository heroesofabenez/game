<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Utils\AccessDeniedException;

// @codingStandardsIgnoreFile

class RecordNotFoundException extends \Exception
{

}

class MissingPermissionsException extends AccessDeniedException
{

}

class HigherRankException extends AccessDeniedException
{

}

class NameInUseException extends AccessDeniedException
{

}

class InvalidStateException extends \RuntimeException
{

}

class OpponentNotFoundException extends RecordNotFoundException
{

}

class ItemNotFoundException extends RecordNotFoundException
{

}

class ItemNotOwnedException extends AccessDeniedException
{

}

class ItemNotEquipableException extends AccessDeniedException
{

}

class ItemAlreadyEquippedException extends InvalidStateException
{

}

class ItemNotWornException extends InvalidStateException
{

}

class ItemNotDamagedException extends AccessDeniedException
{

}

class InsufficientFundsException extends AccessDeniedException
{

}

class GuildNotFoundException extends RecordNotFoundException
{

}

class PlayerNotFoundException extends RecordNotFoundException
{

}

class PlayerNotInGuildException extends AccessDeniedException
{

}

class NotInGuildException extends AccessDeniedException
{

}

class CannotPromoteHigherRanksException extends HigherRankException
{

}

class CannotDemoteHigherRanksException extends HigherRankException
{

}

class CannotKickHigherRanksException extends HigherRankException
{

}

class CannotPromoteToGrandmasterException extends AccessDeniedException
{

}

class CannotDemoteLowestRankException extends AccessDeniedException
{

}

class CannotHaveMoreDeputiesException extends AccessDeniedException
{

}

class GrandmasterCannotLeaveGuildException extends AccessDeniedException
{

}

class StageNotFoundException extends RecordNotFoundException
{

}

class AreaNotFoundException extends RecordNotFoundException
{

}

class CannotTravelToStageException extends AccessDeniedException
{

}

class CannotTravelToAreaException extends AccessDeniedException
{

}

class NotEnoughExperiencesException extends AccessDeniedException
{

}

class SpecializationAlreadyChosenException extends InvalidStateException
{

}

class CannotChooseSpecializationException extends AccessDeniedException
{

}

class SpecializationNotChosenException extends AccessDeniedException
{

}

class SpecializationNotAvailableException extends \OutOfBoundsException
{

}

class InvalidStatException extends \OutOfBoundsException
{

}

class NoStatPointsAvailableException extends AccessDeniedException
{

}

class CannotSeeRequestException extends AccessDeniedException
{

}

class CannotAcceptRequestException extends AccessDeniedException
{

}

class CannotDeclineRequestException extends AccessDeniedException
{

}

class RequestAlreadyHandledException extends InvalidStateException
{

}

class RequestNotFoundException extends RecordNotFoundException
{

}

class InvalidSkillTypeException extends \OutOfBoundsException
{

}

class SkillNotFoundException extends RecordNotFoundException
{

}

class NoSkillPointsAvailableException extends AccessDeniedException
{

}

class SkillMaxLevelReachedException extends AccessDeniedException
{

}

class CannotLearnSkillException extends AccessDeniedException
{

}

class PetNotFoundException extends RecordNotFoundException
{

}

class PetNotOwnedException extends AccessDeniedException
{

}

class PetNotDeployedException extends InvalidStateException
{

}

class PetAlreadyDeployedException extends InvalidStateException
{

}

class PetNotDeployableException extends AccessDeniedException
{

}

class QuestNotFoundException extends RecordNotFoundException
{

}

class QuestNotStartedException extends InvalidStateException
{

}

class QuestAlreadyStartedException extends InvalidStateException
{

}

class QuestNotFinishedException extends InvalidStateException
{

}

class QuestNotAvailableException extends AccessDeniedException
{

}

class CannotFinishQuestHereException extends AccessDeniedException
{

}

class CannotAcceptQuestHereException extends AccessDeniedException
{

}

class AlreadyFriendsException extends AccessDeniedException
{

}

class FriendshipRequestAlreadySentException extends AccessDeniedException
{

}

class NotFriendsException extends AccessDeniedException
{

}
