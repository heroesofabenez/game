<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Orm\Model as ORM;
use Nette\Localization\ITranslator;

/**
 * Global Chat Control
 *
 * @author Jakub Konečný
 */
final class GlobalChatControl extends ChatControl {
  public function __construct(ORM $orm, IDatabaseAdapter $databaseAdapter,  \Nette\Security\User $user, ITranslator $translator) {
    /** @var \HeroesofAbenez\Orm\QuestStage $stage */
    $stage = $orm->stages->getById($user->identity->stage);
    $stagesIds = $orm->stages->findByArea($stage->area)->fetchPairs(null, "id");
    parent::__construct($databaseAdapter, "area", $stage->area->id, "currentStage", $stagesIds, $translator);
  }
}
?>