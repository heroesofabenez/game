<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Profile
 *
 * @author Jakub Konečný
 */
final class ProfilePresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Profile */
  protected $model;
  /** @var \HeroesofAbenez\Model\Guild */
  protected $guildModel;
  
  public function __construct(\HeroesofAbenez\Model\Profile $model, \HeroesofAbenez\Model\Guild $guildModel) {
    parent::__construct();
    $this->model = $model;
    $this->guildModel = $guildModel;
  }
  
  public function actionDefault(): void {
    $this->forward("view", $this->user->id);
  }
  
  public function renderView(int $id): void {
    $data = $this->model->view($id);
    if(is_null($data)) {
      $this->forward("notfound");
    }
    foreach($data as $key => $value) {
      if($key === "guild" AND is_int($value)) {
        $this->template->guildId = $value;
        $this->template->guildName = $this->guildModel->getGuildName($value);
        $this->template->guildRank = $data["guildrank"];
        continue;
      } elseif($key === "guild" AND $value === "") {
        $this->template->guildId = 0;
        continue;
      }
      if($key === "guildrank") {
        continue;
      }
      $this->template->$key = $value;
    }
  }
}
?>