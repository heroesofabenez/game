<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Orm\Model as ORM;
use Nette\Localization\Translator;

/**
 * Chat Command Location
 *
 * @author Jakub Konečný
 */
final class LocationCommand extends \HeroesofAbenez\Chat\BaseChatCommand
{
    public function __construct(
        private readonly \Nette\Security\User $user,
        private readonly ORM $orm,
        private readonly Translator $translator
    ) {
    }

    public function execute(): string
    {
        /** @var \HeroesofAbenez\Orm\QuestStage $stage */
        $stage = $this->orm->stages->getById($this->user->identity->stage);
        return $this->translator->translate(
            "messages.chat.currentLocation",
            ["stageName" => $stage->name, "areaName" => $stage->area->name]
        );
    }
}
