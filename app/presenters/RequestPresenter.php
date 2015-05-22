<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez as HOA;

/**
 * Presenter Request
 *
 * @author Jakub Konečný
 */
class RequestPresenter extends BasePresenter {
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
    $request = HOA\RequestModel::show($id, $this->db);
    if(!$request) $this->forward("notfound");
    $canShow = HOA\RequestModel::canShow($id, $this->user, $this->db);
    if(!$canShow) {
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
  function actionAccept($id) { }
  
  /**
   * @param int $id Request to decline
   * @return void
   */
  function actionDecline($id) { }
}
