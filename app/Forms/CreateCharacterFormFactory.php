<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form,
    Nextras\Orm\Collection\ICollection;

/**
 * Factory for form CreateCharacter
 *
 * @author Jakub Konečný
 */
final class CreateCharacterFormFactory extends BaseFormFactory {
  /**
   * @param ICollection|\HeroesofAbenez\Orm\CharacterRace[] $races
   * @param ICollection|\HeroesofAbenez\Orm\CharacterClass[] $classes
   */
  public function create(ICollection $races, ICollection $classes): Form {
    $form = parent::createBase();
    $racesList = $classesList = [];
    $form->addText("name", "forms.createCharacter.nameField.label")
      ->setRequired("forms.createCharacter.nameField.empty")
      ->addRule(Form::MAX_LENGTH, "forms.createCharacter.nameField.error", 30);
    $form->addRadioList("gender", "forms.createCharacter.genderRadio.label", [ 1 => "male", 2 => "female"])
      ->setRequired("forms.createCharacter.genderRadio.error")
      ->getSeparatorPrototype()->setName(null);
    foreach($races as $value) {
      $racesList[$value->id] = "races.{$value->id}.name";
    }
    $form->addSelect("race", "forms.createCharacter.raceSelect.label", $racesList)
      ->setPrompt("forms.createCharacter.raceSelect.prompt")
      ->setRequired("forms.createCharacter.raceSelect.error");
    $form["race"]->getControlPrototype()->onchange("changeRaceDescription(this.value)");
    foreach($classes as $value) {
      $classesList[$value->id] = "classes.{$value->id}.name";
    }
    $form->addSelect("class", "forms.createCharacter.classSelect.label", $classesList)
      ->setPrompt("forms.createCharacter.classSelect.prompt")
      ->setRequired("forms.createCharacter.classSelect.error");
    $form["class"]->getControlPrototype()->onchange("changeClassDescription(this.value)");
    $form->addSubmit("create", "forms.createCharacter.createButton.label");
    return $form;
  }
}
?>