<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Orm\Model as ORM,
    Nette\Localization\ITranslator;

/**
 * Chat Command Location
 *
 * @author Jakub Konečný
 */
class LocationCommand extends \HeroesofAbenez\Chat\ChatCommand {
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ORM */
  protected $orm;
  /** @var ITranslator */
  protected $translator;
  
  public function __construct(\Nette\Security\User $user, ORM $orm, ITranslator $translator) {
    $this->user = $user;
    $this->orm = $orm;
    $this->translator = $translator;
  }
  
  public function execute(): string {
    /** @var \HeroesofAbenez\Orm\QuestStage $stage */
    $stage = $this->orm->stages->getById($this->user->identity->stage);
    return $this->translator->translate("messages.chat.currentLocation", 0, ["stageName" => $stage->name, "areaName" => $stage->area->name]);
  }
}
?>