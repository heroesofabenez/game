<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild;
use HeroesofAbenez\Model\NotInGuildException;
use HeroesofAbenez\Model\MissingPermissionsException;
use HeroesofAbenez\Model\PlayerNotFoundException;
use HeroesofAbenez\Model\PlayerNotInGuildException;
use HeroesofAbenez\Model\CannotPromoteHigherRanksException;
use HeroesofAbenez\Model\CannotPromoteToGrandmasterException;
use HeroesofAbenez\Model\CannotHaveMoreDeputiesException;
use Nette\Localization\ITranslator;

/**
 * Chat Command Promote
 *
 * @author Jakub Konečný
 */
final class PromoteCommand extends \HeroesofAbenez\Chat\ChatCommand {
  protected Guild $model;
  protected ITranslator $translator;
  
  public function __construct(Guild $model, ITranslator $translator) {
    $this->model = $model;
    $this->translator = $translator;
  }
  
  public function execute(int $id = null): string {
    if($id === null) {
      return "";
    }
    try {
      $this->model->promote($id);
      $message = $this->translator->translate("messages.guild.promoted");
    } catch(NotInGuildException $e) {
      $message = $this->translator->translate("errors.guild.notInGuild");
    } catch(MissingPermissionsException $e) {
      $message = $this->translator->translate("errors.guild.missingPermissions");
    } catch(PlayerNotFoundException $e) {
      $message = $this->translator->translate("errors.guild.playerDoesNotExist");
    } catch(PlayerNotInGuildException $e) {
      $message = $this->translator->translate("errors.guild.playerNotInGuild");
    } catch(CannotPromoteHigherRanksException $e) {
      $message = $this->translator->translate("errors.guild.cannotPromoteHigherRanks");
    } catch(CannotPromoteToGrandmasterException $e) {
      $message = $this->translator->translate("errors.guild.cannotPromoteToGrandmaster");
    } catch(CannotHaveMoreDeputiesException $e) {
      $message = $this->translator->translate("errors.guild.cannotHaveMoreDeputies");
    }
    return $message;
  }
}
?>