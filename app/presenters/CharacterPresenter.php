<?php
use Nette\Application\UI;

  /**
   * Presenter Character
   * 
   * @author Jakub Konečný
   */
class CharacterPresenter extends BasePresenter {
  /**
   * @return void
   */
  function renderCreate() {
    $races = $this->db->table("character_races");
    $this->template->races = array();
    foreach($races as $race) {
      $this->template->races[$race->id] = $race->description;
    }
    $classes = $this->db->table("character_classess");
    $this->template->classes = array();
    foreach($classes as $class) {
      $this->template->classes[$class->id] = $class->description;
    }
  }
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
    $racesList = CharacterModel::getRacesList($this->db);
    $form->addSelect('race', 'Race:', $racesList)
         ->setPrompt('Select race')
         ->setRequired("Select race");
    $form["race"]->getControlPrototype()->onchange("changeRaceDescription(this.value)");
    $classesList = CharacterModel::getClassesList($this->db);
    $form->addSelect('class', 'Class:', $classesList)
         ->setPrompt('Select class')
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
    $data = array(
      "name" => $values["name"], "race" => $values["race"],
      "occupation" => $values["class"], "gender" => $values["gender"]
    );
    $race = $this->db->table("character_races")->get($values["race"]);
    $class = $this->db->table("character_classess")->get($values["class"]);
    $data["strength"] = $class->strength + $race->strength;
    $data["dexterity"] = $class->dexterity + $race->dexterity;
    $data["constitution"] = $class->constitution + $race->constitution;
    $data["intelligence"] = $class->intelligence + $race->intelligence;
    $data["charisma"] = $class->charisma + $race->charisma;
    $data["owner"] = BasePresenter::getRealId();
    
    $chars = $this->db->table("characters")->where("name", $data["name"]);
    if($chars->count("*") > 0) $this->forward("Character:exists");
    
    $this->db->query("INSERT INTO characters", $data);
    
    $data["class"] = $class->name;
    $data["race"] = $race->name;
    if($data["gender"]  == 1) $data["gender"] = "male";
    else $data["gender"] = "female";
    unset($data["occupation"]);
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
