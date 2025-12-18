<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class PermissionsTest extends \Tester\TestCase
{
    use \Testbench\TCompiledContainer;

    private Permissions $model;

    public function setUp(): void
    {
        $this->model = $this->getService(Permissions::class); // @phpstan-ignore assign.propertyType
    }

    public function testGetPermissions(): void
    {
        $permissions = $this->model->getPermissions();
        Assert::type("array", $permissions);
        Assert::count(7, $permissions);
        Assert::type("array", $permissions[1]);
        Assert::same("master", $permissions[1]["role"]);
        Assert::same("manage", $permissions[1]["action"]);
        Assert::type("array", $permissions[2]);
        Assert::same("advisor", $permissions[2]["role"]);
        Assert::same("invite", $permissions[2]["action"]);
        Assert::type("array", $permissions[3]);
        Assert::same("master", $permissions[3]["role"]);
        Assert::same("promote", $permissions[3]["action"]);
        Assert::type("array", $permissions[4]);
        Assert::same("deputy", $permissions[4]["role"]);
        Assert::same("rename", $permissions[4]["action"]);
        Assert::type("array", $permissions[5]);
        Assert::same("deputy", $permissions[5]["role"]);
        Assert::same("kick", $permissions[5]["action"]);
        Assert::type("array", $permissions[6]);
        Assert::same("grandmaster", $permissions[6]["role"]);
        Assert::same("dissolve", $permissions[6]["action"]);
        Assert::type("array", $permissions[7]);
        Assert::same("deputy", $permissions[7]["role"]);
        Assert::same("changeRankNames", $permissions[7]["action"]);
    }

    public function testGetRoles(): void
    {
        $roles = $this->model->getRoles();
        Assert::type("array", $roles);
        Assert::count(7, $roles);
        Assert::same("recruit", $roles[1]);
        Assert::same("member", $roles[2]);
        Assert::same("regular", $roles[3]);
        Assert::same("advisor", $roles[4]);
        Assert::same("master", $roles[5]);
        Assert::same("deputy", $roles[6]);
        Assert::same("grandmaster", $roles[7]);
    }

    public function testGetRankId(): void
    {
        Assert::same(1, $this->model->getRankId("recruit"));
        Assert::null($this->model->getRankId("abc"));
    }
}

$test = new PermissionsTest();
$test->run();
