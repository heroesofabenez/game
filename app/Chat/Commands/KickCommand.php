<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild,
    HeroesofAbenez\Model\NotInGuildException,
    HeroesofAbenez\Model\MissingPermissionsException,
    HeroesofAbenez\Model\PlayerNotFoundException,
    HeroesofAbenez\Model\PlayerNotInGuildException,
    HeroesofAbenez\Model\CannotKickHigherRanksException,
    Nette\Localization\ITranslator;

/**
 * Chat Command Kick
 *
 * @author Jakub Konečný
 */
class KickCommand extends \HeroesofAbenez\Chat\ChatCommand {
  /** @var  Guild */
  protected $model;
  /** @var ITranslator */
  protected $translator;
  
  public function __construct(Guild $model, ITranslator $translator) {
    $this->model = $model;
    $this->translator = $translator;
  }
  
  public function execute(int $id = NULL): string {
    if(is_null($id)) {
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