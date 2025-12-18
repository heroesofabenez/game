<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\Quest as QuestEntity;
use HeroesofAbenez\Orm\CharacterQuest;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class QuestTest extends \Tester\TestCase
{
    use TCharacterControl;

    private Quest $model;

    public function setUp(): void
    {
        $this->model = $this->getService(Quest::class); // @phpstan-ignore assign.propertyType
    }

    public function testListOfQuests(): void
    {
        $quests = $this->model->listOfQuests();
        Assert::type("array", $quests);
        Assert::type(QuestEntity::class, $quests[1]);
        $quests = $this->model->listOfQuests(1);
        $count = count($quests);
        Assert::type("array", $quests);
        foreach ($quests as $index => $quest) {
            Assert::type("int", $index);
            Assert::type(QuestEntity::class, $quest);
            Assert::same(1, $quest->npcStart->id);
        }
        $quests = $this->model->listOfQuests();
        Assert::type("array", $quests);
        Assert::true(count($quests) > $count);
        foreach ($quests as $index => $quest) {
            Assert::type("int", $index);
            Assert::type(QuestEntity::class, $quest);
        }
    }

    public function testView(): void
    {
        /** @var QuestEntity $quest */
        $quest = $this->model->view(1);
        Assert::type(QuestEntity::class, $quest);
        Assert::same(1, $quest->id);
        Assert::null($this->model->view(5000));
    }

    public function testGetCharacterQuest(): void
    {
        $result = $this->model->getCharacterQuest(3);
        Assert::type(CharacterQuest::class, $result);
        Assert::same($this->getCharacter()->id, $result->character->id);
        Assert::same(3, $result->quest->id);
        Assert::same(CharacterQuest::PROGRESS_STARTED, $result->progress);
        $result = $this->model->getCharacterQuest(4);
        Assert::type(CharacterQuest::class, $result);
        Assert::same($this->getCharacter()->id, $result->character->id);
        Assert::same(4, $result->quest->id);
        Assert::same(CharacterQuest::PROGRESS_OFFERED, $result->progress);
        Assert::same(0, $result->arenaWins);
        Assert::same(0, $result->guildDonation);
        Assert::same(0, $result->activeSkillsLevel);
        Assert::same(0, $result->friends);
    }

    public function testStatus(): void
    {
        Assert::same(CharacterQuest::PROGRESS_STARTED, $this->model->status(3));
        Assert::same(CharacterQuest::PROGRESS_OFFERED, $this->model->status(5000));
    }

    public function testIsFinished(): void
    {
        Assert::false($this->model->isFinished(1));
        Assert::false($this->model->isFinished(4));
    }

    public function testIsCompleted(): void
    {
        /** @var \HeroesofAbenez\Orm\Model $orm */
        $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
        $character = $this->getCharacter();
        $quest = new QuestEntity();
        $quest->neededMoney = $character->money + 1;
        $quest->neededItem = $orm->items->getById(6);
        $quest->itemAmount = 2;
        $quest->neededArenaWins = 10;
        $quest->neededGuildDonation = 10;
        $quest->neededActiveSkillsLevel = 3;
        $quest->neededFriends = 3;
        $characterQuest = new CharacterQuest();
        $characterQuest->character = $character;
        $characterQuest->quest = $quest;
        $characterQuest->started = new \DateTimeImmutable();
        Assert::false($this->model->isCompleted($characterQuest));
        $quest->neededMoney = $character->money;
        Assert::false($this->model->isCompleted($characterQuest));
        $quest->itemAmount = 1;
        Assert::false($this->model->isCompleted($characterQuest));
        $quest->neededArenaWins = 0;
        $quest->neededGuildDonation = 0;
        $quest->neededActiveSkillsLevel = 2;
        Assert::false($this->model->isCompleted($characterQuest));
        $quest->neededFriends = 2;
        Assert::true($this->model->isCompleted($characterQuest));
        $orm->remove($quest);
        $orm->remove($characterQuest);
        $orm->flush();
    }

    public function testFinish(): void
    {
        Assert::exception(function () {
            $this->model->finish(5000, 1);
        }, QuestNotFoundException::class);
        Assert::exception(function () {
            $this->model->finish(2, 1);
        }, QuestNotStartedException::class);
        Assert::exception(function () {
            $this->model->finish(3, 2);
        }, CannotFinishQuestHereException::class);
        Assert::exception(function () {
            $this->model->finish(3, 1);
        }, QuestNotFinishedException::class);
    }

    public function testIsAvailable(): void
    {
        /** @var \HeroesofAbenez\Orm\Model $orm */
        $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
        $character = $this->getCharacter();
        $quest = new QuestEntity();
        $quest->requiredLevel = $character->level + 1;
        $quest->requiredClass = $orm->classes->getById(1);
        $quest->requiredRace = $orm->races->getById(1);
        $quest->requiredQuest = $orm->quests->getById(1);
        $quest->requiredWhiteKarma = $character->whiteKarma + 1;
        $quest->requiredDarkKarma = $character->darkKarma + 1;
        Assert::false($this->model->isAvailable($quest));
        $quest->requiredLevel = $character->level;
        Assert::false($this->model->isAvailable($quest));
        $quest->requiredClass = $character->class;
        Assert::false($this->model->isAvailable($quest));
        $quest->requiredRace = $character->race;
        Assert::false($this->model->isAvailable($quest));
        $quest->requiredQuest = null;
        Assert::false($this->model->isAvailable($quest));
        $quest->requiredWhiteKarma = $character->whiteKarma;
        Assert::false($this->model->isAvailable($quest));
        $quest->requiredDarkKarma = $character->darkKarma;
        Assert::true($this->model->isAvailable($quest));
        $this->modifyCharacter(["whiteKarma" => 5,], function () use ($quest) {
            $quest->requiredWhiteKarma = 5;
            $quest->requiredDarkKarma = 0;
            Assert::true($this->model->isAvailable($quest));
        });
        $this->modifyCharacter(["darkKarma" => 5,], function () use ($quest) {
            $quest->requiredDarkKarma = 5;
            $quest->requiredWhiteKarma = 0;
            Assert::true($this->model->isAvailable($quest));
        });
        $orm->removeAndFlush($quest);
    }

    public function testAccept(): void
    {
        Assert::exception(function () {
            $this->model->accept(5000, 1);
        }, QuestNotFoundException::class);
        Assert::exception(function () {
            $this->model->accept(3, 1);
        }, QuestAlreadyStartedException::class);
        Assert::exception(function () {
            $this->model->accept(4, 2);
        }, CannotAcceptQuestHereException::class);
        Assert::exception(function () {
            $this->model->accept(4, 1);
        }, QuestNotAvailableException::class);
    }

    public function testGetRequirements(): void
    {
        /** @var \HeroesofAbenez\Orm\Model $orm */
        $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
        $quest = new QuestEntity();
        $quest->requiredLevel = 99;
        $quest->neededMoney = 1;
        $quest->neededItem = $orm->items->getById(6);
        $quest->itemAmount = 2;
        $quest->neededArenaWins = 10;
        $quest->neededGuildDonation = 100;
        $quest->neededActiveSkillsLevel = 2;
        $quest->neededFriends = 2;
        $quest->npcStart = $orm->npcs->getById(1); // @phpstan-ignore assign.propertyType
        $quest->npcEnd = $orm->npcs->getById(1); // @phpstan-ignore assign.propertyType
        $characterQuest = new CharacterQuest();
        $characterQuest->character = $this->getCharacter();
        $characterQuest->quest = $quest;
        $orm->persistAndFlush($characterQuest);
        $requirements = $this->model->getRequirements($quest);
        Assert::type("array", $requirements);
        Assert::count(7, $requirements);
        Assert::same("pay 1 silver mark", $requirements[0]->text);
        Assert::false($requirements[0]->met);
        Assert::contains("get 2x ", $requirements[1]->text);
        Assert::contains("Apprentice's Wand", $requirements[1]->text);
        Assert::false($requirements[1]->met);
        Assert::same("win 10 fights in arena", $requirements[2]->text);
        Assert::false($requirements[2]->met);
        Assert::same("donate 100 silver marks to your guild", $requirements[3]->text);
        Assert::false($requirements[3]->met);
        Assert::same("have active skills at least at level 2", $requirements[4]->text);
        Assert::true($requirements[4]->met);
        Assert::same("have 2 friends", $requirements[5]->text);
        Assert::true($requirements[5]->met);
        Assert::contains("report back to ", $requirements[6]->text);
        Assert::contains("Mentor", $requirements[6]->text);
        Assert::false($requirements[6]->met);
        $quest->itemAmount = 1;
        $quest->neededActiveSkillsLevel = 3;
        $quest->neededFriends = 3;
        $quest->npcEnd = $orm->npcs->getById(2); // @phpstan-ignore assign.propertyType
        $requirements = $this->model->getRequirements($quest);
        Assert::contains("get 1x ", $requirements[1]->text);
        Assert::true($requirements[1]->met);
        Assert::same("have active skills at least at level 3", $requirements[4]->text);
        Assert::false($requirements[4]->met);
        Assert::same("have 3 friends", $requirements[5]->text);
        Assert::false($requirements[5]->met);
        Assert::contains("talk to ", $requirements[6]->text);
        Assert::contains("Librarian", $requirements[6]->text);
        Assert::false($requirements[6]->met);
        $orm->remove($characterQuest);
        $orm->remove($quest);
        $orm->flush();
    }
}

$test = new QuestTest();
$test->run();
