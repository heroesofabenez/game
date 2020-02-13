<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Orm\Model as ORM;
use Nette\Localization\ITranslator;

/**
 * Chat Command Location
 *
 * @author Jakub Konečný
 */
final class LocationCommand extends \HeroesofAbenez\Chat\ChatCommand {
  protected \Nette\Security\User $user;
  protected ORM $orm;
  protected ITranslator $translator;
  
  public function __construct(\Nette\Security\User $user, ORM $orm, ITranslator $translator) {
    $this->user = $user;
    $this->orm = $orm;
    $this->translator = $translator;
  }
  
  public function execute(): string {
    /** @var \HeroesofAbenez\Orm\QuestStage $stage */
    $stage = $this->orm->stages->getById($this->user->identity->stage);
    $stageName = $this->translator->translate("stages.{$stage->id}.name");
    $areaName = $this->translator->translate("areas.{$stage->area->id}.name");
    return $this->translator->translate("messages.chat.currentLocation", 0, ["stageName" => $stageName, "areaName" => $areaName]);
  }
}
?>