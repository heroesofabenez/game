<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Request
 *
 * @author Jakub Konečný
 */
class RequestPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\RequestModel */
  protected $model;
  
  function startup() {
    parent::startup();
    $this->model = $this->context->getService("model.request");
  }
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
    $request = $this->model->show($id, $this->context);
    if($request === NULL) $this->forward("notfound");
    if(!$request) {
      $this->flashMessage("You can't see this request.");
      $this->forward("Homepage:");
    }
    $this->template->id = $request->id;
    $this->template->from = $request->from;
    $this->template->to = $request->to;
    $this->template->type = $request->type;
    $this->template->sent = $request->sent;
    $this->template->status = $request->status;
  }
  
  /**
   * @param int $id Request to accept
   * @return void
   */
  function actionAccept($id) {
    $result = $this->model->accept($id, $this->context);
    switch($result) {
  case 1:
    $this->flashMessage("Request accepted.");
    $this->redirect("Homepage:");
    break;
  case 2:
    $this->forward("notfound");
    break;
  case 3:
    $this->flashMessage("You can't see this request.");
    $this->forward("Homepage:");
    break;
  case 4:
    $this->flashMessage("You can't accept this request.");
    $this->forward("Homepage:");
    break;
  case 5:
    $this->flashMessage("This request was already handled.");
    $this->forward("Homepage:");
    break;
  case 6:
    $this->forward("Homepage:");
    break;
    }
  }
  
  /**
   * @param int $id Request to decline
   * @return void
   */
  function actionDecline($id) {
    $result = $this->model->decline($id, $this->context);
    switch($result) {
  case 1:
    $this->flashMessage("Request declined.");
    $this->redirect("Homepage:");
    break;
  case 2:
    $this->forward("notfound");
    break;
  case 3:
    $this->flashMessage("You can't see this request.");
    $this->forward("Homepage:");
    break;
  case 4:
    $this->flashMessage("You can't decline this request.");
    $this->forward("Homepage:");
    break;
  case 5:
    $this->flashMessage("This request was already handled.");
    $this->forward("Homepage:");
    break;
    }
  }
}
