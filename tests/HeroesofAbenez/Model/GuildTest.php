<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;
use Tester\Assert;
use HeroesofAbenez\Orm\Guild as GuildEntity;
use Nextras\Orm\Collection\ICollection;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class GuildTest extends \Tester\TestCase
{
    use TCharacterControl;

    private Guild $model;

    public function setUp(): void
    {
        $this->model = $this->getService(Guild::class); // @phpstan-ignore assign.propertyType
    }

    public function testGetGuildName(): void
    {
        Assert::same("", $this->model->getGuildName(5000));
        Assert::same("Dawn", $this->model->getGuildName(1));
    }

    public function testView(): void
    {
        Assert::null($this->model->view(5000));
        /** @var GuildEntity $guild */
        $guild = $this->model->view(1);
        Assert::type(GuildEntity::class, $guild);
        Assert::same(1, $guild->id);
        Assert::same("Dawn", $guild->name);
    }

    public function testCustomRankName(): void
    {
        $guildIds = [1, 2];
        $rankIds = [1, 7];
        foreach ($guildIds as $guildId) {
            foreach ($rankIds as $rankId) {
                $rankName = $this->model->getCustomRankName($guildId, $rankId);
                if ($guildId === 1 and $rankId === 7) {
                    Assert::same("Sun ruler", $rankName);
                } elseif ($guildId === 1 and $rankId === 1) {
                    Assert::same("Sun observer", $rankName);
                } else {
                    Assert::same("", $rankName);
                }
            }
        }
    }

    /**
     * @return array<int, array<int, int>>
     */
    public function getIds(): array
    {
        return [
            [1, 2,]
        ];
    }

    /**
     * @dataProvider getIds
     */
    public function testGuildMembers(int $guild): void
    {
        $members = $this->model->guildMembers($guild, [], true);
        Assert::type("array", $members);
        Assert::type(\stdClass::class, $members[0]);
        Assert::type("string", $members[0]->customRankName);
        if ($guild === 1) {
            Assert::true(strlen($members[0]->customRankName) > 0);
            Assert::count(2, $members);
        } else {
            Assert::same("", $members[0]->customRankName);
            Assert::count(1, $members);
        }
        $members = $this->model->guildMembers($guild, [1]);
        Assert::type("array", $members);
        Assert::count(1, $members);
    }

    public function testCreate(): void
    {
        \Tester\Environment::lock("database", __DIR__ . "/../../..");
        /** @var ORM $orm */
        $orm = $this->getService(ORM::class);
        Assert::exception(function () use ($orm) {
            /** @var GuildEntity $guild */
            $guild = $orm->guilds->getById(1);
            $this->model->create(["name" => $guild->name]);
        }, NameInUseException::class);
        $this->preserveStats(["guild", "guildrank"], function () {
            /** @var ORM $orm */
            $orm = $this->getService(ORM::class);
            $user = $this->getCharacter();
            $data = ["name" => "abc", "description" => "."];
            $this->model->create($data);
            /** @var GuildEntity $guild */
            $guild = $orm->guilds->getByName($data["name"]);
            Assert::type(GuildEntity::class, $guild);
            Assert::same($data["name"], $guild->name);
            Assert::same($guild, $user->guild);
            $orm->guilds->remove($guild);
        });
    }

    public function testListOfGuilds(): void
    {
        $guilds = $this->model->listOfGuilds();
        Assert::type(ICollection::class, $guilds);
        Assert::count(3, $guilds);
    }

    public function testLeave(): void
    {
        \Tester\Environment::lock("database", __DIR__ . "/../../..");
        Assert::exception(function () {
            $this->model->leave();
        }, GrandmasterCannotLeaveGuildException::class);
        $user = $this->getCharacter();
        $guild = $user->guild;
        $guildRank = $user->guildrank;
        $this->modifyCharacter(["guild" => null, "guildrank" => $guildRank], function () {
            Assert::exception(function () {
                $this->model->leave();
            }, NotInGuildException::class);
        });
        $this->modifyCharacter(["guild" => $guild, "guildrank" => 1], function () {
            $this->model->leave();
            $user = $this->getCharacter();
            Assert::null($user->guild);
            Assert::null($user->guildrank);
        });
    }

    public function testRename(): void
    {
        Assert::exception(function () {
            /** @var GuildEntity $guild */
            $guild = $this->model->view(2);
            $this->model->rename(1, $guild->name);
        }, NameInUseException::class);
        /** @var GuildEntity $guild */
        $guild = $this->model->view(1);
        $oldName = $guild->name;
        $this->model->rename($guild->id, "abc");
        Assert::same("abc", $guild->name);
        $this->model->rename($guild->id, $oldName);
    }

    public function testChangeDescription(): void
    {
        Assert::exception(function () {
            $this->model->changeDescription(50, "abc");
        }, GuildNotFoundException::class);
        /** @var GuildEntity $guild */
        $guild = $this->model->view(1);
        $oldDescription = $guild->description;
        $this->model->changeDescription($guild->id, "abc");
        Assert::same("abc", $guild->description);
        $this->model->changeDescription($guild->id, $oldDescription);
    }

    public function testJoin(): void
    {
        \Tester\Environment::lock("database", __DIR__ . "/../../..");
        $this->preserveStats(["guild", "guildrank"], function () {
            $user = $this->getCharacter();
            $this->model->join($user->id, 2);
            Assert::same(2, $user->guild?->id);
            Assert::same(1, $user->guildrank?->id);
        });
    }

    public function testGetDefaultRankNames(): void
    {
        $names = $this->model->getDefaultRankNames();
        Assert::type("array", $names);
        Assert::count(7, $names);
        Assert::type("string", $names[1]);
    }

    /**
     * @dataProvider getIds
     */
    public function testGetCustomRankNames(int $guild): void
    {
        $names = $this->model->getCustomRankNames($guild);
        Assert::type("array", $names);
        if ($guild > 1) {
            Assert::count(0, $names);
        } else {
            Assert::count(7, $names);
            foreach ($names as $name) {
                Assert::type("string", $name);
            }
            Assert::same("Sun ruler", $names[7]);
            Assert::same("Sun observer", $names[1]);
        }
    }

    public function testDonate(): void
    {
        $this->modifyCharacter(["guild" => null], function () {
            Assert::exception(function () {
                $this->model->donate(1);
            }, NotInGuildException::class);
        });
        Assert::exception(function () {
            $this->model->donate(500000);
        }, InsufficientFundsException::class);
        $character = $this->getCharacter();
        $originalCharacterMoney = $character->money;
        $originalGuildMoney = $character->guild?->money;
        $donation = 1;
        $this->model->donate($donation);
        Assert::same($originalCharacterMoney - $donation, $character->money);
        Assert::same($originalGuildMoney + $donation, $character->guild?->money); // @phpstan-ignore plus.leftNonNumeric
        Assert::same($donation, $character->currentGuildContribution);
        $character->money = $originalCharacterMoney;
        $character->guild->money = $originalGuildMoney; // @phpstan-ignore property.nonObject
        /** @var ORM $orm */
        $orm = $this->getService(ORM::class);
        $orm->characters->persistAndFlush($character);
    }
}

$test = new GuildTest();
$test->run();
