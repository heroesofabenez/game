<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Combat\CharacterAttackSkill;
use HeroesofAbenez\Combat\Equipment;
use HeroesofAbenez\Combat\SkillAttack;
use HeroesofAbenez\Combat\Team;
use HeroesofAbenez\Combat\Weapon;
use HeroesofAbenez\Orm\ArenaFightCount;
use Tester\Assert;
use HeroesofAbenez\Combat\Character;
use HeroesofAbenez\Orm\Model as ORM;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class CombatHelperTest extends \Tester\TestCase {
  private CombatHelper $model;
  private \Nette\Security\User $user;
  private ORM $orm;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp(): void {
    $this->model = $this->getService(CombatHelper::class); // @phpstan-ignore assign.propertyType
    $this->user = $this->getService(\Nette\Security\User::class); // @phpstan-ignore assign.propertyType
    $this->orm = $this->getService(ORM::class); // @phpstan-ignore assign.propertyType
  }
  
  public function testGetPlayer(): void {
    $player = $this->model->getPlayer(1);
    Assert::type(Character::class, $player);
    Assert::same(1, $player->id);
    Assert::same("James The Invisible", $player->name);
    Assert::same(\HeroesofAbenez\Orm\Character::GENDER_MALE, $player->gender);
    Assert::same("2", $player->race);
    Assert::same("wizard", $player->occupation);
    Assert::same("", $player->specialization);
    Assert::same(3, $player->level);
    Assert::same(9, $player->strength);
    Assert::same(10, $player->dexterity);
    Assert::same(10, $player->constitution);
    Assert::same(14, $player->intelligence);
    Assert::same(11, $player->charisma);
    Assert::same(50, $player->maxHitpoints);
    Assert::same(50, $player->hitpoints);
    Assert::same(7, $player->damage);
    Assert::same(30, $player->dodge);
    Assert::same(30, $player->hit);
    Assert::same("5d2+INT/3", $player->initiativeFormula);
    Assert::same(0, $player->defense);
    Assert::same(1, $player->activePet);
    Assert::count(1, $player->pets);
    /** @var \HeroesofAbenez\Combat\Pet $pet */
    $pet = $player->pets[0];
    Assert::type(\HeroesofAbenez\Combat\Pet::class, $pet);
    Assert::same(1, $pet->id);
    Assert::true($pet->deployed);
    Assert::same(Character::STAT_INTELLIGENCE, $pet->bonusStat);
    Assert::same(5, $pet->bonusValue);
    Assert::count(1, $player->equipment);
    /** @var Weapon $equipment */
    $equipment = $player->equipment[0];
    Assert::type(Weapon::class, $equipment);
    Assert::same(6, $equipment->id);
    Assert::same("Apprentice's Wand", $equipment->name);
    Assert::same(Equipment::SLOT_WEAPON, $equipment->slot);
    Assert::same(Weapon::TYPE_STAFF, $equipment->type);
    Assert::true($equipment->worn);
    Assert::same(10, $equipment->durability);
    Assert::same(10, $equipment->maxDurability);
    Assert::same(1, $equipment->rawStrength);
    Assert::count(1, $player->skills);
    /** @var CharacterAttackSkill $skill */
    $skill = $player->skills[0];
    Assert::type(CharacterAttackSkill::class, $skill);
    Assert::same(2, $skill->level);
    Assert::same(120, $skill->damage);
    Assert::same(100, $skill->hitRate);
    Assert::type(SkillAttack::class, $skill->skill);
    Assert::same(3, $skill->skill->id);
    Assert::same("Blast", $skill->skill->name);
    Assert::same(SkillAttack::TARGET_SINGLE, $skill->skill->target);
    Assert::same(5, $skill->skill->levels);
    Assert::same("115%", $skill->skill->baseDamage);
    Assert::same("5%", $skill->skill->damageGrowth);
    Assert::same(1, $skill->skill->strikes);
    Assert::null($skill->skill->hitRate);
    Assert::exception(function() {
      $this->model->getPlayer(5000);
    }, OpponentNotFoundException::class);
  }
  
  public function testGetArenaNpc(): void {
    $player = $this->model->getArenaNpc(1);
    Assert::type(Character::class, $player);
    Assert::same("pveArenaNpc1", $player->id);
    Assert::same("Div Fast-hands", $player->name);
    Assert::same(\HeroesofAbenez\Orm\Character::GENDER_MALE, $player->gender);
    Assert::same("2", $player->race);
    Assert::same("rogue", $player->occupation);
    Assert::same("", $player->specialization);
    Assert::same(2, $player->level);
    Assert::same(11, $player->strength);
    Assert::same(13, $player->dexterity);
    Assert::same(8, $player->constitution);
    Assert::same(10, $player->intelligence);
    Assert::same(10, $player->charisma);
    Assert::same(40, $player->maxHitpoints);
    Assert::same(40, $player->hitpoints);
    Assert::same(6, $player->damage);
    Assert::same(39, $player->dodge);
    Assert::same(39, $player->hit);
    Assert::same("2d3+DEX/4", $player->initiativeFormula);
    Assert::same(0, $player->defense);
    Assert::null($player->activePet);
    Assert::count(0, $player->pets);
    Assert::count(1, $player->skills);
    /** @var CharacterAttackSkill $skill */
    $skill = $player->skills[0];
    Assert::type(CharacterAttackSkill::class, $skill);
    Assert::same(1, $skill->level);
    Assert::same(61, $skill->damage);
    Assert::same(100, $skill->hitRate);
    Assert::type(SkillAttack::class, $skill->skill);
    Assert::same(2, $skill->skill->id);
    Assert::same("Shadow strike", $skill->skill->name);
    Assert::same(SkillAttack::TARGET_SINGLE, $skill->skill->target);
    Assert::same(5, $skill->skill->levels);
    Assert::same("61%", $skill->skill->baseDamage);
    Assert::same("2%", $skill->skill->damageGrowth);
    Assert::same(2, $skill->skill->strikes);
    Assert::null($skill->skill->hitRate);
    Assert::count(2, $player->equipment);
    /** @var Weapon $equipment */
    $equipment = $player->equipment[0];
    Assert::type(Weapon::class, $equipment);
    Assert::same(4, $equipment->id);
    Assert::same("Rookie's Dagger", $equipment->name);
    Assert::same(Equipment::SLOT_WEAPON, $equipment->slot);
    Assert::same(Weapon::TYPE_DAGGER, $equipment->type);
    Assert::true($equipment->worn);
    Assert::same(10, $equipment->durability);
    Assert::same(10, $equipment->maxDurability);
    Assert::same(1, $equipment->rawStrength);
    /** @var Equipment $equipment */
    $equipment = $player->equipment[1];
    Assert::type(Equipment::class, $equipment);
    Assert::same(9, $equipment->id);
    Assert::same("Rookie's Cloak", $equipment->name);
    Assert::same(Equipment::SLOT_ARMOR, $equipment->slot);
    Assert::null($equipment->type);
    Assert::true($equipment->worn);
    Assert::same(10, $equipment->durability);
    Assert::same(10, $equipment->maxDurability);
    Assert::same(1, $equipment->rawStrength);
    $player = $this->model->getArenaNpc(2);
    Assert::type(Character::class, $player);
    Assert::same("pveArenaNpc2", $player->id);
    Assert::same("El-Tovil", $player->name);
    Assert::same(\HeroesofAbenez\Orm\Character::GENDER_MALE, $player->gender);
    Assert::same("4", $player->race);
    Assert::same("fighter", $player->occupation);
    Assert::same("", $player->specialization);
    Assert::same(2, $player->level);
    Assert::same(12, $player->strength);
    Assert::same(9, $player->dexterity);
    Assert::same(15, $player->constitution);
    Assert::same(8, $player->intelligence);
    Assert::same(8, $player->charisma);
    Assert::same(75, $player->maxHitpoints);
    Assert::same(75, $player->hitpoints);
    Assert::same(6, $player->damage);
    Assert::same(27, $player->dodge);
    Assert::same(27, $player->hit);
    Assert::same("1d5+DEX/4", $player->initiativeFormula);
    Assert::same(0, $player->defense);
    Assert::null($player->activePet);
    Assert::count(0, $player->pets);
    Assert::count(1, $player->skills);
    /** @var CharacterAttackSkill $skill */
    $skill = $player->skills[0];
    Assert::type(CharacterAttackSkill::class, $skill);
    Assert::same(1, $skill->level);
    Assert::same(110, $skill->damage);
    Assert::same(100, $skill->hitRate);
    Assert::type(SkillAttack::class, $skill->skill);
    Assert::same(1, $skill->skill->id);
    Assert::same("Assault", $skill->skill->name);
    Assert::same(SkillAttack::TARGET_SINGLE, $skill->skill->target);
    Assert::same(5, $skill->skill->levels);
    Assert::same("110%", $skill->skill->baseDamage);
    Assert::same("5%", $skill->skill->damageGrowth);
    Assert::same(1, $skill->skill->strikes);
    Assert::null($skill->skill->hitRate);
    Assert::count(3, $player->equipment);
    /** @var Weapon $equipment */
    $equipment = $player->equipment[0];
    Assert::type(Weapon::class, $equipment);
    Assert::same(3, $equipment->id);
    Assert::same("Novice's Axe", $equipment->name);
    Assert::same(Equipment::SLOT_WEAPON, $equipment->slot);
    Assert::same(Weapon::TYPE_AXE, $equipment->type);
    Assert::true($equipment->worn);
    Assert::same(10, $equipment->durability);
    Assert::same(10, $equipment->maxDurability);
    Assert::same(2, $equipment->rawStrength);
    /** @var Equipment $equipment */
    $equipment = $player->equipment[1];
    Assert::type(Equipment::class, $equipment);
    Assert::same(12, $equipment->id);
    Assert::same("Novice's Shield", $equipment->name);
    Assert::same(Equipment::SLOT_SHIELD, $equipment->slot);
    Assert::null($equipment->type);
    Assert::true($equipment->worn);
    Assert::same(15, $equipment->durability);
    Assert::same(15, $equipment->maxDurability);
    Assert::same(2, $equipment->rawStrength);
    /** @var Equipment $equipment */
    $equipment = $player->equipment[2];
    Assert::type(Equipment::class, $equipment);
    Assert::same(8, $equipment->id);
    Assert::same("Leather Armor", $equipment->name);
    Assert::same(Equipment::SLOT_ARMOR, $equipment->slot);
    Assert::null($equipment->type);
    Assert::true($equipment->worn);
    Assert::same(15, $equipment->durability);
    Assert::same(15, $equipment->maxDurability);
    Assert::same(2, $equipment->rawStrength);
    Assert::exception(function() {
      $this->model->getArenaNpc(5000);
    }, OpponentNotFoundException::class);
  }

  public function testGetCommonNpc(): void {
    Assert::exception(function() {
      $this->model->getCommonNpc(5000);
    }, OpponentNotFoundException::class);
    $player = $this->model->getCommonNpc(1);
    Assert::type(Character::class, $player);
    Assert::same("commonNpc1", $player->id);
    Assert::same("Mentor", $player->name);
    Assert::same("male", $player->gender);
    Assert::same("2", $player->race);
    Assert::same("wizard", $player->occupation);
    Assert::same("", $player->specialization);
    Assert::same(10, $player->level);
    Assert::same(9, $player->strength);
    Assert::same(10, $player->dexterity);
    Assert::same(9, $player->constitution);
    Assert::same(26, $player->intelligence);
    Assert::same(13, $player->charisma);
    Assert::same(45, $player->maxHitpoints);
    Assert::same(45, $player->hitpoints);
    Assert::same(5, $player->damage);
    Assert::same(30, $player->dodge);
    Assert::same(30, $player->hit);
    Assert::same("5d2+INT/3", $player->initiativeFormula);
    Assert::same(0, $player->defense);
    Assert::null($player->activePet);
    Assert::count(0, $player->pets);
    Assert::count(0, $player->skills);
    Assert::count(0, $player->equipment);
  }
  
  public function testGetNumberOfTodayArenaFights(): void {
    $actual = $this->model->getNumberOfTodayArenaFights($this->user->id);
    Assert::type("int", $actual);
    Assert::same(0, $actual);
  }
  
  public function testBumpNumberOfTodayArenaFights(): void {
    $this->model->bumpNumberOfTodayArenaFights($this->user->id, false);
    $result = $this->model->getNumberOfTodayArenaFights($this->user->id);
    Assert::same(1, $result);
    $this->model->bumpNumberOfTodayArenaFights($this->user->id, false);
    $result = $this->model->getNumberOfTodayArenaFights($this->user->id);
    Assert::same(2, $result);
  }

  public function testGetHealers(): void {
    $ids = range(1, 31);
    $characters = new Team("");
    foreach($ids as $id) {
      $characters[] = $this->model->getArenaNpc($id);
    }
    /** @var Team|Character[] $result */
    $result = $this->model->getHealers($characters, new Team(""));
    Assert::type(Team::class, $result);
    Assert::count(2, $result);
    /** @var Character $character */
    $character = $result[0];
    Assert::same("pveArenaNpc18", $character->id);
    /** @var Character $character */
    $character = $result[1];
    Assert::same("pveArenaNpc29", $character->id);
  }

  public function shutDown(): void {
    /** @var ArenaFightCount $record */
    $record = $this->orm->arenaFightsCount->getByCharacterAndDay($this->user->id, date("d.m.Y"));
    $this->orm->arenaFightsCount->removeAndFlush($record);
  }
}

$test = new CombatHelperTest();
$test->run();
?>