<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild;
use HeroesofAbenez\Model\NotInGuildException;
use HeroesofAbenez\Model\MissingPermissionsException;
use HeroesofAbenez\Model\PlayerNotFoundException;
use HeroesofAbenez\Model\PlayerNotInGuildException;
use HeroesofAbenez\Model\CannotKickHigherRanksException;
use Nette\Localization\ITranslator;

/**
 * Chat Command Kick
 *
 * @author Jakub Konečný
 */
final class KickCommand extends \HeroesofAbenez\Chat\ChatCommand {
  private Guild $model;
  private ITranslator $translator;

  public function __construct(Guild $model, ITranslator $translator) {
    $this->model = $model;
    $this->translator = $translator;
  }
  
  public function execute(int $id = null): string {
    if($id === null) {
      return "";
    }
    try {
      $this->model->kick($id);
      $message = $this->translator->translate("messages.guild.kicked");
    } catch(NotInGuildException $e) {
      $message = $this->translator->translate("errors.guild.notInGuild");
    } catch(MissingPermissionsException $e) {
      $message = $this->translator->translate("errors.guild.missingPermissions");
    } catch(PlayerNotFoundException $e) {
      $message = $this->translator->translate("errors.guild.playerDoesNotExist");
    } catch(PlayerNotInGuildException $e) {
      $message = $this->translator->translate("errors.guild.playerNotInGuild");
    } catch(CannotKickHigherRanksException $e) {
      $message = $this->translator->translate("errors.guild.cannotKickHigherRanks");
    }
    return $message;
  }
}
?>