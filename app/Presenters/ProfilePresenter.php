<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\AlreadyFriendsException;
use HeroesofAbenez\Model\FriendshipRequestAlreadySentException;

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
  /** @var \HeroesofAbenez\Model\Friends */
  protected $friendsModel;

  /**
   * ProfilePresenter constructor.
   * @param \HeroesofAbenez\Model\Profile $model
   * @param \HeroesofAbenez\Model\Guild $guildModel
   * @param \HeroesofAbenez\Model\Friends $friendsModel
   */
  public function __construct(\HeroesofAbenez\Model\Profile $model, \HeroesofAbenez\Model\Guild $guildModel, \HeroesofAbenez\Model\Friends $friendsModel) {
    parent::__construct();
    $this->model = $model;
    $this->guildModel = $guildModel;
    $this->friendsModel = $friendsModel;
  }
  
  public function actionDefault(): void {
    $this->forward("view", $this->user->id);
  }

  /**
   * @throws \Nette\Application\BadRequestException
   */
  public function renderView(int $id): void {
    $data = $this->model->view($id);
    if(is_null($data)) {
      throw new \Nette\Application\BadRequestException();
    }
    foreach($data as $key => $value) {
      if($key === "guild" && is_int($value)) {
        $this->template->guildId = $value;
        $this->template->guildName = $this->guildModel->getGuildName($value);
        $this->template->guildRank = $data["guildrank"];
        continue;
      } elseif($key === "guild" && $value === "") {
        $this->template->guildId = 0;
        continue;
      }
      if($key === "guildrank") {
        continue;
      }
      $this->template->$key = $value;
    }
    $this->template->canBefriend = !$this->friendsModel->isFriendsWith($id);
  }

  public function handleBefriend(int $id): void {
    try {
      $this->friendsModel->befriend($id);
    } catch(AlreadyFriendsException $e) {
      $this->flashMessage("errors.friendship.alreadyFriends");
    } catch(FriendshipRequestAlreadySentException $e) {
      $this->flashMessage("errors.friendship.alreadyRequested");
    }
    if(!isset($e)) {
      $this->flashMessage("messages.request.sent");
    }
    $this->redirect("this");
  }
}
?>