<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form;

/**
 * Factory for form DissolveGuildForm
 *
 * @author Jakub Konečný
 */
final class DissolveGuildFormFactory extends BaseFormFactory {
  /** @var \HeroesofAbenez\Model\Guild */
  protected $model;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(\Nette\Localization\ITranslator $translator, \HeroesofAbenez\Model\Guild $model, \Nette\Security\User $user) {
    $this->model = $model;
    $this->user = $user;
    parent::__construct($translator);
  }
  
  public function  create(): Form {
    $form = parent::createBase();
    $currentName = $this->model->getGuildName($this->user->identity->guild);
    $form->addText("name", "forms.dissolveGuild.nameField.label")
      ->addRule(Form::EQUAL, "forms.dissolveGuild.nameField.error", $currentName)
      ->setRequired();
    $form->addSubmit("dissolve", "forms.dissolveGuild.dissolveButton.label");
    $form->onSuccess[] = [$this, "process"];
    return $form;
  }
  
  public function process(): void {
    $gid = $this->user->identity->guild;
    $this->model->dissolve($gid);
  }
}
?>