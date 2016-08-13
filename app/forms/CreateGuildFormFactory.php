<?php
namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form,
    HeroesofAbenez\Model\NameInUseException;

/**
 * Factory for form CreateGuild
 *
 * @author Jakub Konečný
 */
class CreateGuildFormFactory extends BaseFormFactory {
  /** @var \HeroesofAbenez\Model\Guild */
  protected $model;
  
  function __construct(\Nette\Localization\ITranslator $translator, \HeroesofAbenez\Model\Guild $model) {
    $this->model = $model;
    parent::__construct($translator);
  }
  
  /**
   * @return Form
   */
  function create() {
    $form = parent::createBase();
    $form->addText("name", "forms.createGuild.nameField.label")
         ->setRequired("forms.createGuild.nameField.empty")
         ->addRule(Form::MAX_LENGTH, "forms.createGuild.nameField.error", 20);
    $form->addTextArea("description", "forms.createGuild.descriptionField.label")
         ->addRule(Form::MAX_LENGTH, "forms.createGuild.descriptionField.error", 200)
         ->setRequired();
    $form->addSubmit("create", "forms.createGuild.createButton.label");
    $form->onSuccess[] = [$this, "submitted"];
    return $form;
  }
  
  /**
   * @param Form $form
   * @param array $values
   * @return void
   */
  function submitted(Form $form, array $values) {
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