<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\NotImplementedException;
use Nextras\Orm\Collection\ICollection;
use Tester\Assert;
use HeroesofAbenez\Orm\Request as RequestEntity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class RequestTest extends \Tester\TestCase {
  use TCharacterControl;

  private Request $model;
  
  public function setUp() {
    $this->model = $this->getService(Request::class);
  }
  
  public function testCanShow() {
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $request = new RequestEntity();
    $request->type = RequestEntity::TYPE_FRIENDSHIP;
    $request->from = $this->getCharacter();
    Assert::true($this->model->canShow($request));
    $request->from = $orm->characters->getById(2);
    $request->to = $this->getCharacter();
    Assert::true($this->model->canShow($request));
    $request->to = $orm->characters->getById(2);
    Assert::false($this->model->canShow($request));
    $request->type = RequestEntity::TYPE_GROUP_JOIN;
    $request->from = $this->getCharacter();
    Assert::true($this->model->canShow($request));
    $request->from = $orm->characters->getById(2);
    $request->to = $this->getCharacter();
    Assert::true($this->model->canShow($request));
    $request->to = $orm->characters->getById(2);
    Assert::false($this->model->canShow($request));
    $request->type = RequestEntity::TYPE_GUILD_JOIN;
    $request->to = $this->getCharacter();
    Assert::true($this->model->canShow($request));
    $request->from = $this->getCharacter();
    $request->to = $orm->characters->getById(2);
    Assert::true($this->model->canShow($request));
    $request->from = $orm->characters->getById(2);
    Assert::true($this->model->canShow($request));
    $request->from = $orm->characters->getById(4);
    Assert::false($this->model->canShow($request));
    $request->type = RequestEntity::TYPE_GUILD_APP;
    $request->from = $this->getCharacter();
    Assert::true($this->model->canShow($request));
    $request->from = $orm->characters->getById(4);
    $request->to = $this->getCharacter();
    Assert::true($this->model->canShow($request));
    $request->to = $orm->characters->getById(4);
    Assert::false($this->model->canShow($request));
    $orm->removeAndFlush($request);
  }

  public function testCanChange() {
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $request = new RequestEntity();
    $request->from = $this->getCharacter();
    Assert::false($this->model->canChange($request));
    $request->from = $orm->characters->getById(3);
    $request->to = $this->getCharacter();
    Assert::true($this->model->canChange($request));
    $request->to = $orm->characters->getById(2);
    $request->type = RequestEntity::TYPE_GUILD_APP;
    Assert::true($this->model->canChange($request));
    $request->to = $orm->characters->getById(4);
    Assert::false($this->model->canChange($request));
    $orm->removeAndFlush($request);
  }
  
  public function testShow() {
    Assert::type(RequestEntity::class, $this->model->show(1));
    Assert::exception(function() {
      $this->model->show(5000);
    }, RequestNotFoundException::class);
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $request = new RequestEntity();
    $request->type = RequestEntity::TYPE_FRIENDSHIP;
    $request->from = $orm->characters->getById(2);
    $request->to = $orm->characters->getById(4);
    $orm->persistAndFlush($request);
    Assert::exception(function() use ($request) {
      $this->model->show($request->id);
    }, CannotSeeRequestException::class);
    $orm->removeAndFlush($request);
  }
  
  public function testAccept() {
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    Assert::exception(function() {
      $this->model->accept(5000);
    }, RequestNotFoundException::class);
    $request = new RequestEntity();
    $request->type = RequestEntity::TYPE_FRIENDSHIP;
    $request->from = $orm->characters->getById(2);
    $request->to = $orm->characters->getById(4);
    $orm->persistAndFlush($request);
    Assert::exception(function() use ($request) {
      $this->model->accept($request->id);
    }, CannotSeeRequestException::class);
    $request->from = $this->getCharacter();
    $orm->persistAndFlush($request);
    Assert::exception(function() use ($request) {
      $this->model->accept($request->id);
    }, CannotAcceptRequestException::class);
    $request->from = $orm->characters->getById(2);
    $request->to = $this->getCharacter();
    $request->status = RequestEntity::STATUS_ACCEPTED;
    $orm->persistAndFlush($request);
    Assert::exception(function() use ($request) {
      $this->model->accept($request->id);
    }, RequestAlreadyHandledException::class);
    $request->status = RequestEntity::STATUS_NEW;
    $request->type = RequestEntity::TYPE_GROUP_JOIN;
    $orm->persistAndFlush($request);
    Assert::exception(function() use ($request) {
      $this->model->accept($request->id);
    }, NotImplementedException::class);
    // TODO: test other types of request
    $orm->removeAndFlush($request);
  }

  public function testDecline() {
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    Assert::exception(function() {
      $this->model->decline(5000);
    }, RequestNotFoundException::class);
    $request = new RequestEntity();
    $request->type = RequestEntity::TYPE_FRIENDSHIP;
    $request->from = $orm->characters->getById(2);
    $request->to = $orm->characters->getById(4);
    $orm->persistAndFlush($request);
    Assert::exception(function() use ($request) {
      $this->model->decline($request->id);
    }, CannotSeeRequestException::class);
    $request->from = $this->getCharacter();
    $orm->persistAndFlush($request);
    Assert::exception(function() use ($request) {
      $this->model->decline($request->id);
    }, CannotDeclineRequestException::class);
    $request->from = $orm->characters->getById(2);
    $request->to = $this->getCharacter();
    $request->status = RequestEntity::STATUS_DECLINED;
    $orm->persistAndFlush($request);
    Assert::exception(function() use ($request) {
      $this->model->decline($request->id);
    }, RequestAlreadyHandledException::class);
    $request->status = RequestEntity::STATUS_NEW;
    $orm->persistAndFlush($request);
    $this->model->decline($request->id);
    Assert::same(RequestEntity::STATUS_DECLINED, $request->status);
    $orm->removeAndFlush($request);
  }

  public function testListOfRequest() {
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $character = $this->getCharacter();
    $request1 = new RequestEntity();
    $request1->from = $character;
    $request1->to = $orm->characters->getById(2);
    $request1->status = RequestEntity::STATUS_NEW;
    $request1->type = RequestEntity::TYPE_FRIENDSHIP;
    $orm->requests->persist($request1);
    $request2 = new RequestEntity();
    $request2->from = $orm->characters->getById(2);
    $request2->to = $character;
    $request2->status = RequestEntity::STATUS_NEW;
    $request2->type = RequestEntity::TYPE_FRIENDSHIP;
    $orm->requests->persist($request2);
    $request3 = new RequestEntity();
    $request3->from = $character;
    $request3->to = $orm->characters->getById(2);
    $request3->status = RequestEntity::STATUS_ACCEPTED;
    $request3->type = RequestEntity::TYPE_FRIENDSHIP;
    $orm->requests->persist($request3);
    $request4 = new RequestEntity();
    $request4->from = $orm->characters->getById(2);
    $request4->to = $character;
    $request4->status = RequestEntity::STATUS_DECLINED;
    $request4->type = RequestEntity::TYPE_FRIENDSHIP;
    $orm->requests->persist($request4);
    $orm->flush();
    $result = $this->model->listOfRequests();
    Assert::type(ICollection::class, $result);
    Assert::count(2, $result);
    foreach($result as $request) {
      Assert::type(RequestEntity::class, $request);
      Assert::same(RequestEntity::STATUS_NEW, $request->status);
    }
    $orm->requests->remove($request1);
    $orm->requests->remove($request2);
    $orm->requests->remove($request3);
    $orm->requests->remove($request4);
    $orm->flush();
  }
}

$test = new RequestTest();
$test->run();
?>