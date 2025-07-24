<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use HeroesofAbenez\Model\Guild;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;

/**
 * Factory for form DissolveGuildForm
 *
 * @author Jakub Konečný
 */
final class DissolveGuildFormFactory extends BaseFormFactory {
  public function __construct(Translator $translator, private readonly Guild $model, private readonly \Nette\Security\User $user) {
    parent::__construct($translator);
  }
  
  public function  create(): Form {
    $form = $this->createBase();
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