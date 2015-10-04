<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\RequestNotFoundException,
    HeroesofAbenez\Model\CannotSeeRequestException,
    HeroesofAbenez\Model\AccessDenied,
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
   * @return void
   * @throws \Nette\Application\BadRequestException
   */
  function actionDefault() {
    throw new \Nette\Application\BadRequestException;
  }
  
  /**
   * @param int $id Request to show
   * @return void
   */
  function renderView($id) {
    try {
      $request = $this->model->show($id);
      $this->template->id = $request->id;
      $this->template->from = $request->from;
      $this->template->to = $request->to;
      $this->template->type = $request->type;
      $this->template->sent = $request->sent;
      $this->template->status = $request->status;
    } catch(CannotSeeRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.request.cannotSee"));
      $this->forward("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    }
  }
  
  /**
   * @param int $id Request to accept
   * @return void
   */
  function actionAccept($id) {
    try {
      $this->model->accept($id);
      $this->flashMessage($this->translator->translate("messages.request.accepted"));
      $this->redirect("Homepage:");
    } catch(AccessDenied $e) {
      $this->flashMessage($e->getMessage());
      $this->forward("Homepage:");
    } catch(RequestAlreadyHandledException $e) {
      $this->flashMessage($e->getMessage());
      $this->forward("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    } catch(\Nette\NotImplementedException $e) {
      $this->flashMessage($this->translator->translate("errors.request.typeNotImplemented"));
      $this->forward("Homepage:");
    }
  }
  
  /**
   * @param int $id Request to decline
   * @return void
   */
  function actionDecline($id) {
    try {
      $this->model->decline($id);
      $this->flashMessage($this->translator->translate("messages.request.declined"));
      $this->redirect("Homepage:");
    } catch(AccessDenied $e) {
      $this->flashMessage($e->getMessage());
      $this->forward("Homepage:");
    } catch(RequestNotFoundException $e) {
      $this->forward("notfound");
    } catch(RequestAlreadyHandledException $e) {
      $this->flashMessage($e->getMessage());
      $this->forward("Homepage:");
    }
  }
}
?>