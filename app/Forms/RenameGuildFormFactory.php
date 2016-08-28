<?php
namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form,
    HeroesofAbenez\Model\NameInUseException;

/**
 * Factory for form RenameGuildForm
 *
 * @author Jakub Konečný
 */
class RenameGuildFormFactory extends BaseFormFactory {
  /** @var \HeroesofAbenez\Model\Guild */
  protected $model;
  /** @var \Nette\Security\User */
  protected $user;
  
  function __construct(\Nette\Localization\ITranslator $translator, \HeroesofAbenez\Model\Guild $model, \Nette\Security\User $user) {
    $this->model = $model;
    $this->user = $user;
    parent::__construct($translator);
  }

  /**
   * @return Form
   */
  function create() {
    $form = parent::createBase();
    $currentName = $this->model->getGuildName($this->user->identity->guild);
    $form->addText("name", "forms.renameGuild.nameField.label")
         ->addRule(Form::MAX_LENGTH, "forms.renameGuild.nameField.error", 20)
         ->setDefaultValue($currentName)
         ->setRequired();
    $form->addSubmit("rename", "forms.renameGuild.renameButton.label");
    $form->onSuccess[] = [$this, "submitted"];
    return $form;
  }
  
  /**
   * @param Form $form
   * @param array $values
   * @return void
   */
  function submitted(Form $form, array $values) {
    $gid = $this->user->identity->guild;
    $name = $values["name"];
    try {
      $this->model->rename($gid, $name);
    } catch(NameInUseException $e) {
      $form->addError($this->translator->translate("errors.guild.nameTaken"));
    }
  }
}
?>