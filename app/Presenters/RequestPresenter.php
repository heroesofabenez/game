<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\RequestNotFoundException,
    HeroesofAbenez\Model\CannotSeeRequestException,
    HeroesofAbenez\Model\CannotAcceptRequestException,
    HeroesofAbenez\Model\CannotDeclineRequestException,
    HeroesofAbenez\Model\RequestAlreadyHandledException;

/**
 * Presenter Request
 *
 * @author Jakub Konečný
 */
class RequestPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Request @autowire */
  protected $model;
  
  /**
   * Page /request does not exist
   *
   * @throws \Nette\Application\BadRequestException
   */
  function actionDefault(): void {
    throw new \Nette\Application\BadRequestException;
  }
  
  function renderView(int $id): void {
    try {
      $this->template->request = $this->model->show($id);
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotSee"));
      $this->forward("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    }
  }
  
  function actionAccept(int $id): void {
    try {
      $this->model->accept($id);
      $this->flashMessage($this->translator->translate("messages.request.accepted"));
      $this->redirect("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotSee"));
    } catch(CannotAcceptRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotAccept"));
    } catch(RequestAlreadyHandledException $e) {
      $this->flashMessage($this->translator->translate("errors.request.handled"));
      $this->forward("Homepage:");
    } catch(\Nette\NotImplementedException $e) {
      $this->flashMessage($this->translator->translate("errors.request.typeNotImplemented"));
      $this->forward("Homepage:");
    }
  }
  
  function actionDecline(int $id): void {
    try {
      $this->model->decline($id);
      $this->flashMessage($this->translator->translate("messages.request.declined"));
      $this->redirect("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotSee"));
    } catch(CannotDeclineRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotDecline"));
    } catch(RequestAlreadyHandledException $e) {
      $this->flashMessage($this->translator->translate("errors.request.handled"));
      $this->forward("Homepage:");
    }
  }
}
?>