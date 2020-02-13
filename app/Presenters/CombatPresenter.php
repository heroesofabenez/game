<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Combat
 *
 * @author Jakub Konečný
 */
final class CombatPresenter extends BasePresenter {
  protected \HeroesofAbenez\Model\CombatLogManager $log;
  
  public function __construct(\HeroesofAbenez\Model\CombatLogManager $log) {
    parent::__construct();
    $this->log = $log;
  }

  /**
   * @throws \Nette\Application\BadRequestException
   */
  public function actionView(int $id): void {
    $combat = $this->log->read($id);
    if($combat === null) {
      throw new \Nette\Application\BadRequestException();
    }
    $this->template->log = $combat->text;
  }
}
?>