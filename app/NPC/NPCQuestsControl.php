<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Model,
    Kdyby\Translation\Translator;

/**
 * NPC Quests Control
 *
 * @author Jakub Konečný
 * @property-write \HeroesofAbenez\Entities\NPC $npc
 */
class NPCQuestsControl extends \Nette\Application\UI\Control {
  /** @var \HeroesofAbenez\Model\Quest */
  protected $questModel;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Kdyby\Translation\Translator */
  protected $translator;
  /** @var \HeroesofAbenez\Entities\NPC */
  protected $npc;
  
  function __construct(Model\Quest $questModel, Model\Item $itemModel, \Nette\Database\Context $db, \Nette\Security\User $user, Translator $translator) {
    parent::__construct();
    $this->questModel = $questModel;
    $this->itemModel = $itemModel;
    $this->user = $user;
    $this->db = $db;
    $this->translator = $translator;
  }
  
  function setNpc(\HeroesofAbenez\Entities\NPC $npc) {
    $this->npc = $npc;
  }
  
  /**
   * Gets list of available quests from the npc
   *
   * @return \HeroesofAbenez\Entities\Quest[]
   */
  function getQuests(): array {
    $return = $this->questModel->listOfQuests($this->npc->id);
    $playerQuests = $this->db->table("character_quests")
      ->where("character", $this->user->id);
    foreach($return as $key => $quest) {
      foreach($playerQuests as $pquest) {
        if($quest->id == $pquest->quest AND $pquest->progress > 2) {
          unset($return[$key]);
          continue 2;
        } elseif($quest->id == $pquest->quest AND $pquest->progress <= 2) {
          $quest->progress = true;
          continue 2;
        }
      }
      if($quest->needed_level > 0) {
        if($this->user->identity->level < $quest->needed_level) {
          unset($return[$key]);
        }
      } elseif($quest->needed_quest > 0) {
        if(!$this->questModel->isFinished($quest->id)) {
          unset($return[$key]);
        }
      }
    }
    return $return;
  }
  
  /**
   * @return void
   */
  function render(): void {
    $this->template->setFile(__DIR__ . "/npcQuests.latte");
    $this->template->id = $this->npc->id;
    $this->template->quests = $this->getQuests();
    $this->template->render();
  }
  
  /**
   * Accept a quest
   * 
   * @param int $questId Quest's id
   * @return void
   */
  function handleAccept(int $questId): void {
    $quest = $this->questModel->view($questId);
    if(!$quest) {
      $this->presenter->forward("notfound");
    }
    $status = $this->questModel->status($questId);
    if($status > 0) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.workingOn"));
      $this->presenter->redirect("Npc:quests", $quest->npc_start);
    }
    if($quest->npc_start != $this->npc->id) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.cannotAcceptHere"));
      $this->presenter->redirect("Homepage:default");
    }
    $data = [
      "character" => $this->user->id, "quest" => $questId
    ];
    $this->db->query("INSERT INTO character_quests", $data);
    $this->presenter->flashMessage($this->translator->translate("messages.quest.accepted"));
    $this->presenter->redirect("Quest:view", $quest->id);
  }
  
  /**
   * Checks if the player accomplished specified quest's goals
   * 
   * @param \HeroesofAbenez\Entities\Quest $quest
   * @return bool
   */
  protected function isCompleted(\HeroesofAbenez\Entities\Quest $quest): bool {
    $haveMoney = $haveItem = false;
    if($quest->cost_money > 0) {
      $char = $this->db->table("characters")->get($this->user->id);
      if($char->money >= $quest->cost_money) {
        $haveMoney = true;
      }
    } else {
      $haveMoney = true;
    }
    if(is_int($quest->needed_item)) {
      $haveItem = $this->itemModel->haveItem($quest->needed_item, $quest->item_amount);
    } else {
      $haveItem = true;
    }
    return ($haveMoney AND $haveItem);
  }
  
  /**
   * Finish a quest
   * 
   * @param int $questId Quest's id
   * @return void
   */
  function handleFinish(int $questId): void {
    $quest = $this->questModel->view($questId);
    if(!$quest) {
      $this->presenter->forward("notfound");
    }
    $status = $this->questModel->status($questId);
    if($status === 0) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.notWorkingOn"));
      $this->presenter->redirect("Npc:quests", $quest->npc_start);
    }
    if($quest->npc_end != $this->npc->id) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.cannotFinishHere"));
      $this->presenter->redirect("Homepage:default");
    }
    if(!$this->isCompleted($quest)) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.requirementsNotMet"));
      $this->presenter->redirect("Homepage:default");
    }
    $wheres = [
      "character" => $this->user->id, "quest" => $questId
    ];
    $data = ["progress" => 3];
    $this->db->query("UPDATE character_quests SET ? WHERE ?", $data, $wheres);
    if($quest->item_lose) {
      $this->itemModel->loseItem($quest->needed_item, $quest->item_amount);
    }
    if($quest->cost_money > 0) {
      $data3 = "money=money-{$quest->cost_money}";
    } else {
      $data3 = "money=money+{$quest->reward_money}";
    }
    $data3 .= ", experience=experience+{$quest->reward_xp}";
    $where3 = ["id" => $this->user->id];
    $this->db->query("UPDATE characters SET $data3 WHERE ?", $where3);
    if($quest->reward_item > 0) {
      $this->itemModel->giveItem($quest->reward_item);
    }
    $this->presenter->flashMessage($this->translator->translate("messages.quest.finished"));
    $this->presenter->redirect("Quest:view", $quest->id);
  }
}
?>