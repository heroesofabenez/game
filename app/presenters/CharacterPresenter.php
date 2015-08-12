<?php
namespace HeroesofAbenez\Presenters;

use Nette\Application\UI\Form;

  /**
   * Presenter Character
   * 
   * @author Jakub Konečný
   */
class CharacterPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Character @autowire */
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
    $racesIds = $classesIds = array();
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
  protected function createComponentCreateCharacterForm() {
    $form = new Form;
    $form->translator = $this->translator;
    $form->addText("name", "forms.createCharacter.nameField.label")
         ->setRequired("forms.createCharacter.nameField.empty")
         ->addRule(Form::MAX_LENGTH, "forms.createCharacter.nameField.error", 30);
    $form->addRadioList("gender", "forms.createCharacter.genderRadio.label", array( 1 => "male", 2 => "female"))
         ->setRequired("forms.createCharacter.genderRadio.error")
         ->getSeparatorPrototype()->setName(NULL);
    foreach($this->races as $key => &$value) {
      $value = "races.$key.name";
    }
    $form->addSelect("race", "forms.createCharacter.raceSelect.label", $this->races)
         ->setPrompt("forms.createCharacter.raceSelect.prompt")
         ->setRequired("forms.createCharacter.raceSelect.error");
    $form["race"]->getControlPrototype()->onchange("changeRaceDescription(this.value)");
    foreach($this->classes as $key => &$value) {
      $value = "classes.$key.name";
    }
    $form->addSelect("class", "forms.createCharacter.classSelect.label", $this->classes)
         ->setPrompt("forms.createCharacter.classSelect.prompt")
         ->setRequired("forms.createCharacter.classSelect.error");
    $form["class"]->getControlPrototype()->onchange("changeClassDescription(this.value)");
    $form->addSubmit("create", "forms.createCharacter.createButton.label");
    $form->onSuccess[] = array($this, "createCharacterFormSucceeded");
    return $form;
  }
  
  /**
   * Handles creating character
   * @param \Nette\Application\UI\Form $form Sent form
   * @param \Nette\Utils\ArrayHash $values Array vith values
   * @return void
   */
  function createCharacterFormSucceeded(Form $form, $values) {
    $data = $this->$userManager->create($values);
    if(!$data) $this->forward("Character:exists");
    $this->user->logout();
    $this->forward("Character:created", array("data" => serialize($data)));
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