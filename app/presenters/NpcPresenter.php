<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez as HOA;

/**
 * Presenter Npc
 *
 * @author Jakub Konečný
 */
class NpcPresenter extends BasePresenter {
  function renderDefault($id) {
    $npc = HOA\NPCModel::view($id, $this->context);
    if(!$npc) $this->forward("notfound");
    if($npc->stage !== $this->user->identity->stage) $this->forward("unavailable");
    $this->template->name = $npc->name;
    $this->template->description = $npc->description;
  }
}