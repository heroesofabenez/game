<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild,
    HeroesofAbenez\Model\NotInGuildException,
    HeroesofAbenez\Model\MissingPermissionsException,
    HeroesofAbenez\Model\PlayerNotFoundException,
    HeroesofAbenez\Model\PlayerNotInGuildException,
    HeroesofAbenez\Model\CannotDemoteHigherRanksException,
    HeroesofAbenez\Model\CannotDemoteLowestRankException,
    HeroesofAbenez\Model\CannotPromoteToGrandmasterException,
    Nette\Localization\ITranslator,
    Nette\Utils\Arrays;

/**
 * Chat Command Demote
 *
 * @author Jakub Konečný
 */
class DemoteCommand extends \HeroesofAbenez\Chat\ChatCommand {
  /** @var Guild */
  protected $model;
  /** @var ITranslator */
  protected $translator;
  
  public function __construct(Guild $model, ITranslator $translator) {
    $this->model = $model;
    $this->translator = $translator;
  }
  
  /**
   * @param int $id
   */
  public function execute(): string {
    $id = Arrays::get(func_get_args(), 0, "");
    if($id === "" OR !is_numeric($id)) {
      return "";
    }
    try {
      $this->model->demote((int) $id);
      $message = $this->translator->translate("messages.guild.demoted");
    } catch(NotInGuildException $e) {
      $message = $this->translator->translate("errors.guild.notInGuild");
    } catch(MissingPermissionsException $e) {
      $message = $this->translator->translate("errors.guild.missingPermissions");
    } catch(PlayerNotFoundException $e) {
      $message = $this->translator->translate("errors.guild.playerDoesNotExist");
    } catch(PlayerNotInGuildException $e) {
      $message = $this->translator->translate("errors.guild.playerNotInGuild");
    } catch(CannotDemoteHigherRanksException $e) {
      $message = $this->translator->translate("errors.guild.cannotPromoteHigherRanks");
    } catch(CannotPromoteToGrandmasterException $e) {
      $message = $this->translator->translate("errors.guild.cannotDemoteHigherRanks");
    } catch(CannotDemoteLowestRankException $e) {
      $message = $this->translator->translate("errors.guild.cannotDemoteLowestRank");
    }
    return $message;
  }
}
?>