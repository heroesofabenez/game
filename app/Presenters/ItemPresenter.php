<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Item
 *
 * @author Jakub Konečný
 */
final class ItemPresenter extends BasePresenter {
  protected \HeroesofAbenez\Model\Item $model;
  
  public function __construct(\HeroesofAbenez\Model\Item $model) {
    parent::__construct();
    $this->model = $model;
  }

  /**
   * @throws \Nette\Application\BadRequestException
   */
  public function renderView(int $id): void {
    $item = $this->model->view($id);
    if($item === null) {
      throw new \Nette\Application\BadRequestException();
    }
    $this->template->item = $item;
    $this->template->level = $this->user->identity->level;
    $this->template->class = $this->user->identity->class;
    $this->template->specialization = $this->user->identity->specialization;
  }
}
?>