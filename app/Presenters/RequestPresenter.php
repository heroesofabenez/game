<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\RequestNotFoundException;
use HeroesofAbenez\Model\CannotSeeRequestException;
use HeroesofAbenez\Model\CannotAcceptRequestException;
use HeroesofAbenez\Model\CannotDeclineRequestException;
use HeroesofAbenez\Model\RequestAlreadyHandledException;

/**
 * Presenter Request
 *
 * @author Jakub Konečný
 */
final class RequestPresenter extends BasePresenter {
  private \HeroesofAbenez\Model\Request $model;
  
  public function __construct(\HeroesofAbenez\Model\Request $model) {
    parent::__construct();
    $this->model = $model;
  }
  
  /**
   * Page /request does not exist
   *
   * @throws \Nette\Application\BadRequestException
   */
  public function actionDefault(): void {
    throw new \Nette\Application\BadRequestException();
  }

  /**
   * @throws \Nette\Application\BadRequestException
   */
  public function renderView(int $id): void {
    try {
      $this->template->request = $this->model->show($id);
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage("errors.request.cannotSee");
      $this->forward("Homepage:");
    } catch(RequestNotFoundException $e) {
      throw new \Nette\Application\BadRequestException();
    }
  }
  
  public function actionAccept(int $id): void {
    try {
      $this->model->accept($id);
      $this->flashMessage("messages.request.accepted");
      $this->redirect("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage("errors.request.cannotSee");
      $this->forward("Homepage:");
    } catch(CannotAcceptRequestException $e) {
      $this->flashMessage("errors.request.cannotAccept");
      $this->forward("Homepage:");
    } catch(RequestAlreadyHandledException $e) {
      $this->flashMessage("errors.request.handled");
      $this->forward("Homepage:");
    } catch(\Nette\NotImplementedException $e) {
      $this->flashMessage("errors.request.typeNotImplemented");
      $this->forward("Homepage:");
    }
  }
  
  public function actionDecline(int $id): void {
    try {
      $this->model->decline($id);
      $this->flashMessage("messages.request.declined");
      $this->redirect("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage("errors.request.cannotSee");
      $this->forward("Homepage:");
    } catch(CannotDeclineRequestException $e) {
      $this->flashMessage("errors.request.cannotDecline");
      $this->forward("Homepage:");
    } catch(RequestAlreadyHandledException $e) {
      $this->flashMessage("errors.request.handled");
      $this->forward("Homepage:");
    }
  }
}
?>