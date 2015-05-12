<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Error
 *
 * @author Jakub Konečný
 */
class ErrorPresenter extends BasePresenter {
  function actionDefault($exception) {
    if($exception instanceof Nette\Application\BadRequestException) {
      $this->setView("404");
    } else {
      $this->setView("500");
    }
  }
}
