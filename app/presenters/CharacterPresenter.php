<?php
namespace HeroesofAbenez\Presenters;

use \Nette\Application\UI;

  /**
   * Presenter Character
   * 
   * @author Jakub Konečný
   */
class CharacterPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Character */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Character $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function renderCreate() {
    $this->template->races = $this->model->getRacesDescriptions();
    $this->template->classes = $this->model->getClassesDescriptions();
  }
  /**
   * Create form for creating character
   * @return \Nette\Application\UI\Form
   */
  protected function createComponentCreateCharacterForm() {
    $form = new UI\Form;
    $form->addText("name", "Name:")
         ->setRequired("You have to enter name.")
         ->addRule(\Nette\Forms\Form::MAX_LENGTH, "Name can have no more than 30 letters", 30);
    $form->addRadioList("gender", "Gender:", array( 1 => "male", 2 => "female"))
         ->setRequired("Select gender")
         ->getSeparatorPrototype()->setName(NULL);
    $racesList = $this->model->getRacesList();
    $form->addSelect("race", "Race:", $racesList)
         ->setPrompt("Select race")
         ->setRequired("Select race");
    $form["race"]->getControlPrototype()->onchange("changeRaceDescription(this.value)");
    $classesList = $this->model->getClassesList();
    $form->addSelect("class", "Class:", $classesList)
         ->setPrompt("Select class")
         ->setRequired("Select class");
    $form["class"]->getControlPrototype()->onchange("changeClassDescription(this.value)");
    $form->addSubmit("create", "Create character");
    $form->onSuccess[] = array($this, "createCharacterFormSucceeded");
    return $form;
  }
  
  /**
   * Handles creating character
   * @param Nette\Application\UI\Form $form Sent form
   * @param  Nette\Utils\ArrayHash $values Array vith values
   * @return void
   */
  function createCharacterFormSucceeded(UI\Form $form, $values) {
    $data = $this->model->create($values);
    if(!$data) $this->forward("Character:exists");
    $this->user->logout();
    $this->forward("Character:created", array("data" => serialize($data)));
  }
  
  /**
   * @param string $data Serialized array with data
   */
  function renderCreated($data) {
    $data = unserialize($data);
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>