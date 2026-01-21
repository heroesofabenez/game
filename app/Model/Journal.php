<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterQuest as CharacterQuestEntity;
use HeroesofAbenez\Orm\Pet as PetEntity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\QuestStage as QuestStageEntity;
use Nextras\Orm\Collection\ICollection;

/**
 * Journal Model
 *
 * @author Jakub Konečný
 */
final readonly class Journal
{
    public function __construct(private \Nette\Security\User $user, private ORM $orm)
    {
    }

    /**
     * Gets basic info for character's journal
     */
    public function basic(): array
    {
        /** @var \HeroesofAbenez\Orm\Character $character */
        $character = $this->orm->characters->getById($this->user->id);
        /** @var QuestStageEntity $stage */
        $stage = $character->currentStage;
        $return = [
            "name" => $character->name, "gender" => $character->gender, "race" => $character->race->id,
            "class" => $character->class->id,
            "specialization" => ($character->specialization !== null) ? $character->specialization->id : null,
            "level" => $character->level, "whiteKarma" => $character->whiteKarma, "darkKarma" => $character->darkKarma,
            "experiences" => $character->experience, "stageName" => $stage->name, "areaName" => $stage->area->name,
            "predominantKarma" => $character->predominantKarma,
        ];
        $return["guild"] = false;
        if ($character->guild !== null) {
            $return["guild"] = $character->guild->name;
            $return["guildRank"] = ($character->guildrank !== null) ? $character->guildrank->id : "";
        }
        return $return;
    }

    /**
     * Gets character's inventory
     */
    public function inventory(): array
    {
        $return = [];
        /** @var \HeroesofAbenez\Orm\Character $character */
        $character = $this->orm->characters->getById($this->user->id);
        $return["money"] = $character->money;
        $return["items"] = [];
        foreach ($character->items as $item) {
            $i = $item->item;
            $return["items"][] = (object) [
                "id" => $i->id, "name" => $i->name, "amount" => $item->amount, "worn" => $item->worn,
                "eqid" => $item->id, "durability" => $item->durability, "maxDurability" => $item->maxDurability,
                "repairPrice" => $item->repairPrice, "equipable" => $item->item->equipable,
            ];
        }
        return $return;
    }

    /**
     * Gets character's pets
     *
     * @return ICollection|PetEntity[]
     */
    public function pets(): ICollection
    {
        return $this->orm->pets->findByOwner($this->user->id);
    }

    /**
     * Gets character's quests
     *
     * @return ICollection|CharacterQuestEntity[]
     */
    public function currentQuests(): ICollection
    {
        return $this->orm->characterQuests->findBy(
            ["character" => $this->user->id, "progress<" => CharacterQuestEntity::PROGRESS_FINISHED]
        );
    }

    /**
     * Gets character's finished quests
     *
     * @return ICollection|CharacterQuestEntity[]
     */
    public function finishedQuests(): ICollection
    {
        return $this->orm->characterQuests->findBy([
            "character" => $this->user->id, "progress" => CharacterQuestEntity::PROGRESS_FINISHED
        ])->orderBy("id", ICollection::DESC);
    }

    /**
     * @return \HeroesofAbenez\Orm\Character[]
     */
    public function friends(): array
    {
        $userId = $this->user->id;
        $return = [];
        $friendships = $this->orm->friendships->findByCharacter($userId);
        foreach ($friendships as $friendship) {
            if ($friendship->character1->id === $userId) {
                $return[] = $friendship->character2;
            } else {
                $return[] = $friendship->character1;
            }
        }
        return $return;
    }
}
