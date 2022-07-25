<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form;
use HeroesofAbenez\Model\NameInUseException;

/**
 * Factory for form CreateGuild
 *
 * @author Jakub Konečný
 */
final class CreateGuildFormFactory extends BaseFormFactory {
  private \HeroesofAbenez\Model\Guild $model;
  
  public function __construct(\Nette\Localization\ITranslator $translator, \HeroesofAbenez\Model\Guild $model) {
    $this->model = $model;
    parent::__construct($translator);
  }
  
  public function create(): Form {
    $form = $this->createBase();
    $form->addText("name", "forms.createGuild.nameField.label")
      ->setRequired("forms.createGuild.nameField.empty")
      ->addRule(Form::MAX_LENGTH, "forms.createGuild.nameField.error", 20);
    $form->addTextArea("description", "forms.createGuild.descriptionField.label")
      ->addRule(Form::MAX_LENGTH, "forms.createGuild.descriptionField.error", 200)
      ->setRequired();
    $form->addSubmit("create", "forms.createGuild.createButton.label");
    $form->onSuccess[] = [$this, "process"];
    return $form;
  }
  
  public function process(Form $form, array $values): void {
    $data = [
      "name" => $values["name"], "description" => $values["description"]
    ];
    try {
      $this->model->create($data);
    } catch(NameInUseException $e) {
      $form->addError($this->translator->translate("errors.guild.nameTaken"));
    }
  }
}
?>