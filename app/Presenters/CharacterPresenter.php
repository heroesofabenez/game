<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Forms\CreateCharacterFormFactory,
    Nette\Application\UI\Form;

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
  /** @var \HeroesofAbenez\Entities\CharacterClass[] */
  protected $classes;
  /** @var \HeroesofAbenez\Entities\CharacterRace[] */
  protected $races;
  
  /**
   * Get list of races of classes
   * 
   * @return void
   */
  function startup() {
    parent::startup();
    $this->classes = $this->model->getClassesList();
    $this->races = $this->model->getRacesList();
  }
  
  /**
   * @return void
   */
  function renderCreate() {
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
   * @return \Nette\Application\UI\Form
   */
  protected function createComponentCreateCharacterForm(CreateCharacterFormFactory $factory) {
    $form = $factory->create($this->races, $this->classes);
    $form->onSuccess[] = function(Form $form, array $values) {
      $data = $this->userManager->create($values);
      if(!$data) $this->forward("Character:exists");
      $this->user->logout();
      $this->forward("Character:created", ["data" => serialize($data)]);
    };
    return $form;
  }
  
  /**
   * @param string $data Serialized array with data
   * @return void
   */
  function renderCreated($data) {
    $data = unserialize($data);
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>