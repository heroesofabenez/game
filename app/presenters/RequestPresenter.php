<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Request
 *
 * @author Jakub Konečný
 */
class RequestPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Request */
  protected $model;
  
  /**
   * @param \HeroesofAbenez\Model\Request $model
   */
  function __construct(\HeroesofAbenez\Model\Request $model) {
    $this->model = $model;
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
    try {
      $request = $this->model->show($id);
      $this->template->id = $request->id;
      $this->template->from = $request->from;
      $this->template->to = $request->to;
      $this->template->type = $request->type;
      $this->template->sent = $request->sent;
      $this->template->status = $request->status;
    } catch(\Nette\Application\ForbiddenRequestException $e) {
      $this->flashMessage("You can't see this request.");
      $this->forward("Homepage:");
    } catch(\Nette\Application\BadRequestException $e) {
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
      $this->flashMessage("Request accepted.");
      $this->redirect("Homepage:");
    } catch(\Nette\Application\ForbiddenRequestException $e) {
      $this->flashMessage($e->getMessage());
      $this->forward("Homepage:");
    } catch(\Nette\Application\BadRequestException $e) {
      $this->forward("notfound");
    } catch(\Nette\NotImplementedException $e) {
      $this->flashMessage("This type of request is not implemented.");
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
      $this->flashMessage("Request declined.");
      $this->redirect("Homepage:");
    } catch(\Nette\Application\ForbiddenRequestException $e) {
      $this->flashMessage($e->getMessage());
      $this->forward("Homepage:");
    } catch(\Nette\Application\BadRequestException $e) {
      $this->forward("notfound");
    }
  }
}
?>