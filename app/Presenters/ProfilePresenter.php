<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\AlreadyFriendsException;
use HeroesofAbenez\Model\Friends;
use HeroesofAbenez\Model\FriendshipRequestAlreadySentException;
use HeroesofAbenez\Model\Guild;
use HeroesofAbenez\Model\Profile;

/**
 * Presenter Profile
 *
 * @author Jakub Konečný
 */
final class ProfilePresenter extends BasePresenter
{
    public function __construct(private readonly Profile $model, private readonly Guild $guildModel, private readonly Friends $friendsModel)
    {
        parent::__construct();
    }

    public function actionDefault(): never
    {
        $this->forward("view", $this->user->id);
    }

    /**
     * @throws \Nette\Application\BadRequestException
     */
    public function renderView(int $id): void
    {
        $data = $this->model->view($id);
        if ($data === null) {
            throw new \Nette\Application\BadRequestException();
        }
        foreach ($data as $key => $value) {
            if ($key === "guild" && is_int($value)) {
                $this->template->guildId = $value;
                $this->template->guildName = $this->guildModel->getGuildName($value);
                $this->template->guildRank = $data["guildrank"];
                continue;
            } elseif ($key === "guild" && $value === "") {
                $this->template->guildId = 0;
                continue;
            }
            if ($key === "guildrank") {
                continue;
            }
            $this->template->$key = $value;
        }
        $this->template->canBefriend = !$this->friendsModel->isFriendsWith($id);
    }

    public function handleBefriend(int $id): never
    {
        try {
            $this->friendsModel->befriend($id);
        } catch (AlreadyFriendsException $e) {
            $this->flashMessage("errors.friendship.alreadyFriends");
        } catch (FriendshipRequestAlreadySentException $e) {
            $this->flashMessage("errors.friendship.alreadyRequested");
        }
        if (!isset($e)) {
            $this->flashMessage("messages.request.sent");
        }
        $this->redirect("this");
    }
}
