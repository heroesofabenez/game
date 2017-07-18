<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Forms\CreateCharacterFormFactory,
    Nette\Application\UI\Form,
    Nextras\Orm\Collection\ICollection;

  /**
   * Presenter Character
   * 
   * @author Jakub Konečný
   */
class CharacterPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Profile @autowire */
  protected $model;
  /** @var \HeroesofAbenez\Model\UserManager @autowire */
  protected $userManager;
  /** @var ICollection|\HeroesofAbenez\Orm\CharacterClass[] */
  protected $classes;
  /** @var ICollection|\HeroesofAbenez\Orm\CharacterRace[] */
  protected $races;
  
  /**
   * Get list of races of classes
   */
  function startup(): void {
    parent::startup();
    $this->classes = $this->model->getClassesList();
    $this->races = $this->model->getRacesList();
  }
  
  function renderCreate(): void {
    $racesIds = $classesIds = [];
    foreach($this->races as $race) {
      $racesIds[] = $race->id;
    }
    foreach($this->classes as $class) {
      $classesIds[] = $class->id;
    }
    $this->template->races = $racesIds;
    $this->template->classes = $classesIds;
  }
  
  /**
   * Create form for creating character
   */
  protected function createComponentCreateCharacterForm(CreateCharacterFormFactory $factory): Form {
    $form = $factory->create($this->races, $this->classes);
    $form->onSuccess[] = function(Form $form, array $values) {
      $data = $this->userManager->create($values);
      if(!$data) {
        $this->forward("Character:exists");
      }
      $this->user->logout();
      $this->forward("Character:created", ["data" => serialize($data)]);
    };
    return $form;
  }
  
  /**
   * @param string $data Serialized array with data
   */
  function renderCreated(string $data): void {
    $data = unserialize($data);
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>