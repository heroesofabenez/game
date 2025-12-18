<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Friendship
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character1 {m:1 Character, oneSided=true}
 * @property Character $character2 {m:1 Character, oneSided=true}
 */
final class Friendship extends \Nextras\Orm\Entity\Entity
{
}
