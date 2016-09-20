<?php
namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild,
    HeroesofAbenez\Model\NotInGuildException,
    HeroesofAbenez\Model\MissingPermissionsException,
    HeroesofAbenez\Model\PlayerNotFoundException,
    HeroesofAbenez\Model\PlayerNotInGuild,
    HeroesofAbenez\Model\CannotKickHigherRanksException,
    Kdyby\Translation\Translator,
    Nette\Utils\Arrays;

/**
 * Chat Command Kick
 *
 * @author Jakub Konečný
 */
class KickCommand extends \HeroesofAbenez\Entities\ChatCommand {
  /** @var  Guild */
  protected $model;
  /** @var Translator */
  protected $translator;
  
  function __construct(Guild $model, Translator $translator) {
    parent::__construct("kick");
    $this->model = $model;
    $this->translator = $translator;
  }
  
  /**
   * @param int $id
   * @return string
   */
  function execute(): string {
    $id = Arrays::get(func_get_args(), 0, "");
    if($id === "" OR !is_numeric($id)) return "";
    try {
      $this->model->kick((int) $id);
      $message = $this->translator->translate("messages.guild.kicked");
    } catch(NotInGuildException $e) {
      $message = $this->translator->translate("errors.guild.notInGuild");
    } catch(MissingPermissionsException $e) {
      $message = $this->translator->translate("errors.guild.missingPermissions");
    } catch(PlayerNotFoundException $e) {
      $message = $this->translator->translate("errors.guild.playerDoesNotExist");
    } catch(PlayerNotInGuild $e) {
      $message = $this->translator->translate("errors.guild.playerNotInGuild");
    } catch(CannotKickHigherRanksException $e) {
      $message = $this->translator->translate("errors.guild.cannotKickHigherRanks");
    }
    return $message;
  }
}
?>