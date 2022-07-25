<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild;
use HeroesofAbenez\Model\NotInGuildException;
use HeroesofAbenez\Model\MissingPermissionsException;
use HeroesofAbenez\Model\PlayerNotFoundException;
use HeroesofAbenez\Model\PlayerNotInGuildException;
use HeroesofAbenez\Model\CannotDemoteHigherRanksException;
use HeroesofAbenez\Model\CannotDemoteLowestRankException;
use HeroesofAbenez\Model\CannotPromoteToGrandmasterException;
use Nette\Localization\ITranslator;

/**
 * Chat Command Demote
 *
 * @author Jakub Konečný
 */
final class DemoteCommand extends \HeroesofAbenez\Chat\ChatCommand {
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
      $this->model->demote($id);
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