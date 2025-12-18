<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

/**
 * @author Jakub Konečný
 */
final class NextrasOrmAdapterTest extends \Tester\TestCase
{
    use \Testbench\TCompiledContainer;

    private NextrasOrmAdapter $model;
    private \HeroesofAbenez\Orm\Model $orm;

    protected function setUp(): void
    {
        $this->model = $this->getService(NextrasOrmAdapter::class); // @phpstan-ignore assign.propertyType
        $this->orm = $this->getService(\HeroesofAbenez\Orm\Model::class); // @phpstan-ignore assign.propertyType
    }

    public function testGetTexts(): void
    {
        $texts = $this->model->getTexts("guild", 1, 25);
        Assert::type(ChatMessagesCollection::class, $texts);
        Assert::count(4, $texts);
    }

    public function testGetCharacters(): void
    {
        $characters = $this->model->getCharacters("guild", 1);
        Assert::type(ChatCharactersCollection::class, $characters);
    }

    public function testAddMessage(): void
    {
        $texts = $this->model->getTexts("guild", 1, 25);
        Assert::count(4, $texts);
        $this->model->addMessage("test", "guild", 1);
        $texts = $this->model->getTexts("guild", 1, 25);
        Assert::count(5, $texts);
        $this->orm->chatMessages->removeAndFlush($this->orm->chatMessages->getById($texts[0]->id)); // @phpstan-ignore property.notFound, argument.type
    }
}

$test = new NextrasOrmAdapterTest();
$test->run();
