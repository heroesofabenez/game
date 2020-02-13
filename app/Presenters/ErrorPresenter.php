<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Tracy\ILogger;

/**
 * Presenter Error
 *
 * @author Jakub Konečný
 */
final class ErrorPresenter extends BasePresenter {
  private ?ILogger $logger;
  
  public function __construct(ILogger $logger = null) {
    parent::__construct();
    $this->logger = $logger;
  }
  
  public function actionDefault(\Throwable $exception): void {
    if($exception instanceof \Nette\Application\BadRequestException) {
      $this->setView("404");
    } else {
      $this->setView("500");
      if($this->logger !== null) {
        $this->logger->log($exception, ILogger::EXCEPTION);
      }
    }
  }
}
?>