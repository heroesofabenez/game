<?php
namespace HeroesofAbenez\Presenters;

use \HeroesofAbenez as HOA;

  /**
   * Presenter Profile
   * 
   * @author Jakub Konečný
   */
class ProfilePresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Profile */
  protected $model;
  
  function __construct(\HeroesofAbenez\Profile $model) {
    $this->model = $model;
  }
  
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
    $data = $this->model->view($id, $this->context);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      if($key == "guild" AND is_int($value)) {
        $guildName = $this->context->getService("model.guild")->getGuildName($value);
        $guildRank = $this->model->getRankName($data["guildrank"]);
        $guildLink = $this->link("Guild:view", $value);
        $value = "Guild: <a href=\"$guildLink\">$guildName</a><br>Position in guild: " . ucfirst($guildRank);
      }
      if($key == "guildrank") continue;
      $this->template->$key = $value;
    }
  }
}
?>