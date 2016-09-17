<?php
namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild,
    HeroesofAbenez\Model\NotInGuildException,
    HeroesofAbenez\Model\MissingPermissionsException,
    HeroesofAbenez\Model\PlayerNotFoundException,
    HeroesofAbenez\Model\PlayerNotInGuild,
    HeroesofAbenez\Model\CannotDemoteHigherRanksException,
    HeroesofAbenez\Model\CannotDemoteLowestRankException,
    HeroesofAbenez\Model\CannotPromoteToGrandmaster,
    Kdyby\Translation\Translator,
    Nette\Utils\Arrays;

/**
 * Chat Command Demote
 *
 * @author Jakub Konečný
 */
class DemoteCommand extends \HeroesofAbenez\Entities\ChatCommand {
  /** @var  Guild */
  protected $model;
  /** @var Translator */
  protected $translator;
  
  function __construct(Guild $model, Translator $translator) {
    parent::__construct("demote");
    $this->model = $model;
    $this->translator = $translator;
  }
  
  /**
   * @param int $id
   * @return string
   */
  function execute() {
    $id = Arrays::get(func_get_args(), 0, "");
    if($id === "" OR !is_numeric($id)) return "";
    try {
      $this->model->demote((int) $id);
      $message = $this->translator->translate("messages.guild.demoted");
    } catch(NotInGuildException $e) {
      $message = $this->translator->translate("errors.guild.notInGuild");
    } catch(MissingPermissionsException $e) {
      $message = $this->translator->translate("errors.guild.missingPermissions");
    } catch(PlayerNotFoundException $e) {
      $message = $this->translator->translate("errors.guild.playerDoesNotExist");
    } catch(PlayerNotInGuild $e) {
      $message = $this->translator->translate("errors.guild.playerNotInGuild");
    } catch(CannotDemoteHigherRanksException $e) {
      $message = $this->translator->translate("errors.guild.cannotPromoteHigherRanks");
    } catch(CannotPromoteToGrandmaster $e) {
      $message = $this->translator->translate("errors.guild.cannotDemoteHigherRanks");
    } catch(CannotDemoteLowestRankException $e) {
      $message = $this->translator->translate("errors.guild.cannotDemoteLowestRank");
    }
    return $message;
  }
}
?>