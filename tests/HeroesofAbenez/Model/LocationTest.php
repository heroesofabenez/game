<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\RoutesArea;
use HeroesofAbenez\Orm\RoutesStage;
use Tester\Assert;
use HeroesofAbenez\Orm\QuestArea;
use HeroesofAbenez\Orm\QuestStage;
use Nextras\Orm\Collection\ICollection;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class LocationTest extends \Tester\TestCase
{
    use TCharacterControl;

    private Location $model;

    public function setUp(): void
    {
        $this->model = $this->getService(Location::class); // @phpstan-ignore assign.propertyType
    }

    public function testGetStage(): void
    {
        Assert::null($this->model->getStage(5000));
        /** @var QuestStage $stage */
        $stage = $this->model->getStage(1);
        Assert::type(QuestStage::class, $stage);
        Assert::same(1, $stage->id);
        Assert::same("Study Room", $stage->name);
        Assert::same(0, $stage->requiredLevel);
        Assert::null($stage->requiredRace);
        Assert::type(CharacterClass::class, $stage->requiredClass);
        Assert::same(3, $stage->requiredClass?->id);
        Assert::type(QuestArea::class, $stage->area);
        Assert::same(1, $stage->area->id);
        Assert::same(215, $stage->posX);
        Assert::same(65, $stage->posY);
    }

    public function testStageRoutes(): void
    {
        /** @var QuestArea $area */
        $area = $this->model->getArea(1);
        $routes = $this->model->stageRoutes($area);
        Assert::type(ICollection::class, $routes);
        Assert::count(7, $routes);
        /** @var RoutesStage $route */
        $route = $routes->fetch();
        Assert::same(1, $route->id);
        Assert::same(1, $route->from->id);
        Assert::same(2, $route->to->id);
        /** @var RoutesStage $route */
        $route = $routes->fetch();
        Assert::same(2, $route->id);
        Assert::same(1, $route->from->id);
        Assert::same(3, $route->to->id);
        /** @var RoutesStage $route */
        $route = $routes->fetch();
        Assert::same(3, $route->id);
        Assert::same(2, $route->from->id);
        Assert::same(3, $route->to->id);
        /** @var RoutesStage $route */
        $route = $routes->fetch();
        Assert::same(5, $route->id);
        Assert::same(1, $route->from->id);
        Assert::same(6, $route->to->id);
        /** @var RoutesStage $route */
        $route = $routes->fetch();
        Assert::same(6, $route->id);
        Assert::same(2, $route->from->id);
        Assert::same(6, $route->to->id);
        /** @var RoutesStage $route */
        $route = $routes->fetch();
        Assert::same(8, $route->id);
        Assert::same(1, $route->from->id);
        Assert::same(8, $route->to->id);
        /** @var RoutesStage $route */
        $route = $routes->fetch();
        Assert::same(9, $route->id);
        Assert::same(2, $route->from->id);
        Assert::same(8, $route->to->id);
    }

    public function testAreaRoutes(): void
    {
        $routes = $this->model->areaRoutes();
        Assert::type(ICollection::class, $routes);
        foreach ($routes as $route) {
            Assert::type(RoutesArea::class, $route);
        }
    }

    public function testGetArea(): void
    {
        Assert::null($this->model->getArea(5000));
        /** @var QuestArea $area */
        $area = $this->model->getArea(1);
        Assert::type(QuestArea::class, $area);
        Assert::same(1, $area->id);
        Assert::same("Academy of Magic", $area->name);
        Assert::same(0, $area->requiredLevel);
        Assert::null($area->requiredRace);
        Assert::type(CharacterClass::class, $area->requiredClass);
        Assert::same(3, $area->requiredClass?->id);
        Assert::same(250, $area->posX);
        Assert::same(35, $area->posY);
    }

    public function testAccessibleStages(): void
    {
        $this->model->user = $this->getService(\Nette\Security\User::class); // @phpstan-ignore assign.propertyType
        $result = $this->model->accessibleStages();
        Assert::type("array", $result);
        Assert::count(5, $result);
        $stage = $result[1];
        Assert::type(QuestStage::class, $stage);
        Assert::same(1, $stage->id);
        $stage = $result[2];
        Assert::type(QuestStage::class, $stage);
        Assert::same(2, $stage->id);
        $stage = $result[3];
        Assert::type(QuestStage::class, $stage);
        Assert::same(3, $stage->id);
        $stage = $result[6];
        Assert::type(QuestStage::class, $stage);
        Assert::same(6, $stage->id);
        $stage = $result[8];
        Assert::type(QuestStage::class, $stage);
        Assert::same(8, $stage->id);
    }

    public function testAccessibleAreas(): void
    {
        $result = $this->model->accessibleAreas();
        Assert::type("array", $result);
        foreach ($result as $key => $area) {
            Assert::type("int", $key);
            Assert::type(QuestArea::class, $area);
        }
    }

    public function testCanEnterStage(): void
    {
        $this->model->user = $this->getService(\Nette\Security\User::class); // @phpstan-ignore assign.propertyType
        /** @var QuestStage $stage */
        $stage = $this->model->getStage(1);
        Assert::true($this->model->canEnterStage($stage));
        $oldLevel = $stage->requiredLevel;
        $oldRace = $stage->requiredRace;
        $oldClass = $stage->requiredClass;
        $stage->requiredLevel = 999;
        Assert::false($this->model->canEnterStage($stage));
        $stage->requiredLevel = $oldLevel;
        $stage->requiredRace = 1;
        Assert::false($this->model->canEnterStage($stage));
        $stage->requiredRace = $oldRace;
        $stage->requiredClass = 1;
        Assert::false($this->model->canEnterStage($stage));
        $stage->requiredClass = $oldClass;
        /** @var \HeroesofAbenez\Orm\Model $orm */
        $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
        $orm->stages->persistAndFlush($stage);
    }

    public function testTravelToStage(): void
    {
        $this->model->user = $this->getService(\Nette\Security\User::class); // @phpstan-ignore assign.propertyType
        Assert::exception(function () {
            $this->model->travelToStage(5000);
        }, StageNotFoundException::class);
        $this->preserveStats(["currentStage"], function () {
            $this->model->travelToStage(2);
            Assert::same(2, $this->getCharacterStat("currentStage")->id);
        });
        Assert::exception(function () {
            $this->model->travelToStage(4);
        }, CannotTravelToStageException::class);
    }

    public function testCanEnterArea(): void
    {
        $this->model->user = $this->getService(\Nette\Security\User::class); // @phpstan-ignore assign.propertyType
        /** @var QuestArea $area */
        $area = $this->model->getArea(1);
        Assert::true($this->model->canEnterArea($area));
        $oldLevel = $area->requiredLevel;
        $oldRace = $area->requiredRace;
        $oldClass = $area->requiredClass;
        $area->requiredLevel = 999;
        Assert::false($this->model->canEnterArea($area));
        $area->requiredLevel = $oldLevel;
        $area->requiredRace = 1;
        Assert::false($this->model->canEnterArea($area));
        $area->requiredRace = $oldRace;
        $area->requiredClass = 1;
        Assert::false($this->model->canEnterArea($area));
        $area->requiredClass = $oldClass;
        /** @var \HeroesofAbenez\Orm\Model $orm */
        $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
        $orm->areas->persistAndFlush($area);
    }

    public function testTravelToArea(): void
    {
        Assert::exception(function () {
            $this->model->travelToArea(5000);
        }, AreaNotFoundException::class);
    }
}

$test = new LocationTest();
$test->run();
