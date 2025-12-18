<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\NPC\Personalities\NpcPersonality;

final class NpcPersonalityChooser extends \Nexendrie\Utils\Collection
{
    protected string $class = NpcPersonality::class;

    /**
     * @param NpcPersonality[] $items
     */
    public function __construct(array $items)
    {
        parent::__construct();
        foreach ($items as $item) {
            $this[] = $item;
        }
    }

    public function getPersonality(\HeroesofAbenez\Orm\Npc $npc): NpcPersonality
    {
        /** @var NpcPersonality|null $personality */
        $personality = $this->getItem(["getName()" => $npc->personality]);
        if ($personality === null) {
            $personality = new class ($npc->personality) implements NpcPersonality {
                public function __construct(private string $personality)
                {
                }

                public function getName(): string
                {
                    return $this->personality;
                }

                public function getMood(\Nette\Security\IIdentity $user, \HeroesofAbenez\Orm\Npc $npc): string
                {
                    return $this->getName();
                }
            };
        }
        return $personality;
    }
}
