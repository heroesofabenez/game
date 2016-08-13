<?php
namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form;

/**
 * Factory for form CreateCharacter
 *
 * @author Jakub Konečný
 */
class CreateCharacterFormFactory extends BaseFormFactory {
  /**
   * @param \HeroesofAbenez\Entities\CharacterRace[] $races
   * @param \HeroesofAbenez\Entities\CharacterClass[] $classes
   * @return Form
   */
  function create(array $races, array $classes) {
    $form = parent::createBase();
    $form->addText("name", "forms.createCharacter.nameField.label")
         ->setRequired("forms.createCharacter.nameField.empty")
         ->addRule(Form::MAX_LENGTH, "forms.createCharacter.nameField.error", 30);
    $form->addRadioList("gender", "forms.createCharacter.genderRadio.label", [ 1 => "male", 2 => "female"])
         ->setRequired("forms.createCharacter.genderRadio.error")
         ->getSeparatorPrototype()->setName(NULL);
    foreach($races as $key => &$value) {
      $value = "races.$key.name";
    }
    $form->addSelect("race", "forms.createCharacter.raceSelect.label", $races)
         ->setPrompt("forms.createCharacter.raceSelect.prompt")
         ->setRequired("forms.createCharacter.raceSelect.error");
    $form["race"]->getControlPrototype()->onchange("changeRaceDescription(this.value)");
    foreach($classes as $key => &$value) {
      $value = "classes.$key.name";
    }
    $form->addSelect("class", "forms.createCharacter.classSelect.label", $classes)
         ->setPrompt("forms.createCharacter.classSelect.prompt")
         ->setRequired("forms.createCharacter.classSelect.error");
    $form["class"]->getControlPrototype()->onchange("changeClassDescription(this.value)");
    $form->addSubmit("create", "forms.createCharacter.createButton.label");
    return $form;
  }
}
?>