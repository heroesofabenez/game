<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\NotImplementedException;
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
}

$test = new RequestTest();
$test->run();
?>