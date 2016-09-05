<?php
namespace HeroesofAbenez\Chat\Commands;

/**
 * chat Command Location
 *
 * @author Jakub Konečný
 */
class LocationCommand extends \HeroesofAbenez\Entities\ChatCommand {
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Kdyby\Translation\Translator */
  protected $translator;
  
  function __construct(\Nette\Security\User $user, \Nette\Database\Context $db, \Kdyby\Translation\Translator $translator) {
    parent::__construct("location");
    $this->user = $user;
    $this->db = $db;
    $this->translator = $translator;
  }

  
  /**
   * @return string
   */
  function execute() {
    $stageId = $this->user->identity->stage;
    $stage = $this->db->table("quest_stages")->get($stageId);
    $area = $this->db->table("quest_areas")->get($stage->area);
    return $this->translator->translate("messages.chat.currentLocation", ["stageName" => $stage->name, "areaName" => $area->name]);
  }
}
?>