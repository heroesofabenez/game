<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild;
use HeroesofAbenez\Model\NotInGuildException;
use HeroesofAbenez\Model\MissingPermissionsException;
use HeroesofAbenez\Model\PlayerNotFoundException;
use HeroesofAbenez\Model\PlayerNotInGuildException;
use HeroesofAbenez\Model\CannotKickHigherRanksException;
use Nette\Localization\Translator;

/**
 * Chat Command Kick
 *
 * @author Jakub Konečný
 */
final class KickCommand extends \HeroesofAbenez\Chat\ChatCommand {
  public function __construct(private readonly Guild $model, private readonly Translator $translator) {
  }
  
  public function execute(int $id = null): string {
    if($id === null) {
      return "";
    }
    try {
      $this->model->kick($id);
      $message = $this->translator->translate("messages.guild.kicked");
    } catch(NotInGuildException) {
      $message = $this->translator->translate("errors.guild.notInGuild");
    } catch(MissingPermissionsException) {
      $message = $this->translator->translate("errors.guild.missingPermissions");
    } catch(PlayerNotFoundException) {
      $message = $this->translator->translate("errors.guild.playerDoesNotExist");
    } catch(PlayerNotInGuildException) {
      $message = $this->translator->translate("errors.guild.playerNotInGuild");
    } catch(CannotKickHigherRanksException) {
      $message = $this->translator->translate("errors.guild.cannotKickHigherRanks");
    }
    return $message;
  }
}
?>