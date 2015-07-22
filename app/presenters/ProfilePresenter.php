<?php
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
  /** @var \HeroesofAbenez\Model\Permissions @autowire */
  protected $permissionsModel;
  
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
  function renderView($id) {
    $data = $this->model->view($id);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      if($key == "guild" AND is_int($value)) {
        $guildName = $this->guildModel->getGuildName($value);
        $guildRank = $this->permissionsModel->getRoleName($data["guildrank"]);
        $guildLink = $this->link("Guild:view", $value);
        $value = "Guild: <a href=\"$guildLink\">$guildName</a><br>Position in guild: " . ucfirst($guildRank);
      }
      if($key == "guildrank") continue;
      $this->template->$key = $value;
    }
  }
}
?>