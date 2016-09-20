<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Tracy\ILogger;

/**
 * Presenter Error
 *
 * @author Jakub Konečný
 */
class ErrorPresenter extends BasePresenter {
  /** @var ILogger|NULL */
  private $logger;
  
  function __construct(ILogger $logger = NULL) {
    $this->logger = $logger;
  }
  
  /**
   * @param \Throwable $exception
   * @return void
   */
  function actionDefault($exception) {
    if($exception instanceof \Nette\Application\BadRequestException) {
      $this->setView("404");
    } else {
      $this->setView("500");
      if($this->logger) $this->logger->log($exception, ILogger::EXCEPTION);
    }
  }
}
?>