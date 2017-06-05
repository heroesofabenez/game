<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild,
    HeroesofAbenez\Model\NotInGuildException,
    HeroesofAbenez\Model\MissingPermissionsException,
    HeroesofAbenez\Model\PlayerNotFoundException,
    HeroesofAbenez\Model\PlayerNotInGuildException,
    HeroesofAbenez\Model\CannotKickHigherRanksException,
    Nette\Localization\ITranslator,
    Nette\Utils\Arrays;

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
  
  function __construct(Guild $model, ITranslator $translator) {
    $this->model = $model;
    $this->translator = $translator;
  }
  
  /**
   * @param int $id
   * @return string
   */
  function execute(): string {
    $id = Arrays::get(func_get_args(), 0, "");
    if($id === "" OR !is_numeric($id)) {
      return "";
    }
    try {
      $this->model->kick((int) $id);
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