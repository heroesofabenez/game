<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Combat
 *
 * @author Jakub Konečný
 */
final class CombatPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\CombatLogManager */
  protected $log;
  
  public function __construct(\HeroesofAbenez\Model\CombatLogManager $log) {
    parent::__construct();
    $this->log = $log;
  }

  /**
   * @throws \Nette\Application\BadRequestException
   */
  public function actionView(int $id): void {
    $combat = $this->log->read($id);
    if(is_null($combat)) {
      throw new \Nette\Application\BadRequestException();
    }
    $this->template->log = $combat->text;
  }
}
?>