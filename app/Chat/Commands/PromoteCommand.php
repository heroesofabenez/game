<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild,
    HeroesofAbenez\Model\NotInGuildException,
    HeroesofAbenez\Model\MissingPermissionsException,
    HeroesofAbenez\Model\PlayerNotFoundException,
    HeroesofAbenez\Model\PlayerNotInGuildException,
    HeroesofAbenez\Model\CannotPromoteHigherRanksException,
    HeroesofAbenez\Model\CannotPromoteToGrandmasterException,
    HeroesofAbenez\Model\CannotHaveMoreDeputiesException,
    Nette\Localization\ITranslator,
    Nette\Utils\Arrays;

/**
 * Chat Command Promote
 *
 * @author Jakub Konečný
 */
class PromoteCommand extends \HeroesofAbenez\Chat\ChatCommand {
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
   */
  function execute(): string {
    $id = Arrays::get(func_get_args(), 0, "");
    if($id === "" OR !is_numeric($id)) {
      return "";
    }
    try {
      $this->model->promote((int) $id);
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