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
final class RequestPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Request */
  protected $model;
  
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
    throw new \Nette\Application\BadRequestException;
  }
  
  public function renderView(int $id): void {
    try {
      $this->template->request = $this->model->show($id);
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotSee"));
      $this->forward("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    }
  }
  
  public function actionAccept(int $id): void {
    try {
      $this->model->accept($id);
      $this->flashMessage($this->translator->translate("messages.request.accepted"));
      $this->redirect("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotSee"));
      $this->forward("Homepage:");
    } catch(CannotAcceptRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotAccept"));
      $this->forward("Homepage:");
    } catch(RequestAlreadyHandledException $e) {
      $this->flashMessage($this->translator->translate("errors.request.handled"));
      $this->forward("Homepage:");
    } catch(\Nette\NotImplementedException $e) {
      $this->flashMessage($this->translator->translate("errors.request.typeNotImplemented"));
      $this->forward("Homepage:");
    }
  }
  
  public function actionDecline(int $id): void {
    try {
      $this->model->decline($id);
      $this->flashMessage($this->translator->translate("messages.request.declined"));
      $this->redirect("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotSee"));
      $this->forward("Homepage:");
    } catch(CannotDeclineRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotDecline"));
      $this->forward("Homepage:");
    } catch(RequestAlreadyHandledException $e) {
      $this->flashMessage($this->translator->translate("errors.request.handled"));
      $this->forward("Homepage:");
    }
  }
}
?>