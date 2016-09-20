<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

  /**
   * Presenter Profile
   * 
   * @author Jakub Konečný
   */
class ProfilePresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Profile @autowire */
  protected $model;
  /** @var \HeroesofAbenez\Model\Guild @autowire */
  protected $guildModel;
  
  /**
   * @return void
   */
  function actionDefault() {
    $this->forward("view", $this->user->id);
  }
  
  /**
   * @param int $id id of character
   * @return void
   */
  function renderView(int $id) {
    $data = $this->model->view($id);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      if($key == "guild" AND is_int($value)) {
        $this->template->guildId = $value;
        $this->template->guildName = $this->guildModel->getGuildName($value);
        $this->template->guildRank = $data["guildrank"];
        continue;
      } elseif($key == "guild" AND $value === "") {
        $this->template->guildId = 0;
        continue;
      }
      if($key == "guildrank") continue;
      $this->template->$key = $value;
    }
  }
}
?>