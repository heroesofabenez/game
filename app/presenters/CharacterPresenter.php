<?php
namespace HeroesofAbenez\Presenters;

use \Nette\Application\UI;

  /**
   * Presenter Character
   * 
   * @author Jakub Konečný
   */
class CharacterPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Character @autowire */
  protected $model;
  
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
    $form->translator = $this->translator;
    $form->addText("name", "forms.createCharacter.nameField.label")
         ->setRequired("forms.createCharacter.nameField.empty")
         ->addRule(\Nette\Forms\Form::MAX_LENGTH, "forms.createCharacter.nameField.error", 30);
    $form->addRadioList("gender", "forms.createCharacter.genderRadio.label", array( 1 => "male", 2 => "female"))
         ->setRequired("forms.createCharacter.genderRadio.error")
         ->getSeparatorPrototype()->setName(NULL);
    $racesList = $this->model->getRacesList();
    foreach($racesList as $key => &$value) {
      $value = "races.$key.name";
    }
    $form->addSelect("race", "forms.createCharacter.raceSelect.label", $racesList)
         ->setPrompt("forms.createCharacter.raceSelect.prompt")
         ->setRequired("forms.createCharacter.raceSelect.error");
    $form["race"]->getControlPrototype()->onchange("changeRaceDescription(this.value)");
    $classesList = $this->model->getClassesList();
    foreach($classesList as $key => &$value) {
      $value = "classes.$key.name";
    }
    $form->addSelect("class", "forms.createCharacter.classSelect.label", $classesList)
         ->setPrompt("forms.createCharacter.classSelect.prompt")
         ->setRequired("forms.createCharacter.classSelect.error");
    $form["class"]->getControlPrototype()->onchange("changeClassDescription(this.value)");
    $form->addSubmit("create", "forms.createCharacter.createButton.label");
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