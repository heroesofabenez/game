<?php
use Nette\Application\UI;

  /**
   * Presenter Character
   * 
   * @author Jakub Konečný
   */
class CharacterPresenter extends BasePresenter {
  /**
   * Create form for creating character
   * @return Nette\Application\UI\Form
   */
  protected function createComponentCreateCharacterForm() {
    $form = new UI\Form;
    $form->addText("name", "Name:")
         ->setRequired("You have to enter name.")
         ->addRule(Nette\Forms\Form::MAX_LENGTH, "Name can have no more than 30 letters", 30);
    $form->addRadioList('gender', 'Gender:', array( 1 => "male", 2 => "female"))
         ->setRequired("Select gender")
         ->getSeparatorPrototype()->setName(NULL);
    $racesList = array();
    $races = $this->db->table("character_races");
    foreach($races as $race) {
      $racesList[$race->id] = $race->name;
    }
    $form->addSelect('race', 'Race:', $racesList)
         ->setPrompt('Select race')
         ->setRequired("Select race");
    $classesList = array();
    $classes = $this->db->table("character_classess");
    foreach($classes as $class) {
      $classesList[$class->id] = $class->name;
    }
    $form->addSelect('class', 'Class:', $classesList)
         ->setPrompt('Select class')
         ->setRequired("Select class");
    $form->addSubmit("create", "Create character");
    $form->onSuccess[] = array($this, "createCharacterFormSucceeded");
    return $form;
  }
  
  /**
   * Handles creating character
   * @todo implement :P
   * @param Nette\Application\UI\Form $form Sent form
   * @param  Nette\Utils\ArrayHash $values Array vith values
   * @return void
   */
  function createCharacterFormSucceeded(UI\Form $form, $values) {
    $this->flashMessage("Character created.");
    $this->redirect("Homepage:");
  }
}
