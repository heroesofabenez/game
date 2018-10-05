<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Quest
 *
 * @author Jakub Konečný
 */
final class QuestPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Quest */
  protected $model;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var \HeroesofAbenez\Model\NPC */
  protected $npcModel;
  
  public function __construct(\HeroesofAbenez\Model\Quest $model, \HeroesofAbenez\Model\Item $itemModel, \HeroesofAbenez\Model\NPC $npcModel) {
    parent::__construct();
    $this->model = $model;
    $this->itemModel = $itemModel;
    $this->npcModel = $npcModel;
  }
  
  /**
   * Page /quest does not exist
   *
   * @throws \Nette\Application\BadRequestException
   */
  public function actionDefault(): void {
    throw new \Nette\Application\BadRequestException();
  }
  
  public function renderView(int $id): void {
    $quest = $this->model->view($id);
    if(is_null($quest)) {
      $this->forward("notfound");
    }
    $this->template->id = $quest->id;
    $this->template->finished = $this->model->isFinished($id);
    $this->template->npcStart = $quest->npcStart;
    $this->template->npcEnd = $this->translator->translate("npcs.$quest->npcEnd.name");
    $requirements = [];
    if($quest->costMoney > 0) {
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementPayMoney", $quest->costMoney),
        "met" => false
      ];
    }
    if(is_int($quest->neededItem)) {
      $itemName = $this->translator->translate("items.$quest->neededItem.name");
      $itemLink = $this->link("Item:view", $quest->neededItem);
      $haveItem = $this->itemModel->haveItem($quest->neededItem, $quest->itemAmount);
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementGetItem", $quest->itemAmount, ["item" => "<a href=\"$itemLink\">$itemName</a>"]),
        "met" => $haveItem
      ];
    }
    $npcLink = $this->link("Npc:view", $quest->npcEnd);
    if($quest->npcStart != $quest->npcEnd) {
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementTalkToNpc", 0, ["npc" => "<a href=\"$npcLink\">{$this->template->npcEnd}</a>"]),
        "met" => false
      ];
    } else {
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementReportBackToNpc", 0, ["npc" => "<a href=\"$npcLink\">{$this->template->npcEnd}</a>"]),
        "met" => false
      ];
    }
    $this->template->requirements = $requirements;
    $this->template->rewardMoney = $quest->rewardMoney;
    $this->template->rewardXp = $quest->rewardXp;
    $this->template->rewardItem = (is_int($quest->rewardItem)) ? $quest->rewardItem : false;
    $this->template->rewardWhiteKarma = $quest->rewardWhiteKarma;
    $this->template->rewardDarkKarma = $quest->rewardDarkKarma;
  }
}
?>