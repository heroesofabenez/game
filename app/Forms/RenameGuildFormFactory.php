<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form;
use HeroesofAbenez\Model\NameInUseException;

/**
 * Factory for form RenameGuildForm
 *
 * @author Jakub Konečný
 */
final class RenameGuildFormFactory extends BaseFormFactory {
  private \HeroesofAbenez\Model\Guild $model;
  private \Nette\Security\User $user;
  
  public function __construct(\Nette\Localization\ITranslator $translator, \HeroesofAbenez\Model\Guild $model, \Nette\Security\User $user) {
    $this->model = $model;
    $this->user = $user;
    parent::__construct($translator);
  }
  
  public function create(): Form {
    $form = $this->createBase();
    $currentName = $this->model->getGuildName($this->user->identity->guild);
    $form->addText("name", "forms.renameGuild.nameField.label")
      ->addRule(Form::MAX_LENGTH, "forms.renameGuild.nameField.error", 20)
      ->setDefaultValue($currentName)
      ->setRequired();
    $form->addSubmit("rename", "forms.renameGuild.renameButton.label");
    $form->onSuccess[] = [$this, "process"];
    return $form;
  }
  
  public function process(Form $form, array $values): void {
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