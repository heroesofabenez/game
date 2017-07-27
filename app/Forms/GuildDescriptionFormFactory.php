<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form,
    HeroesofAbenez\Model\GuildNotFoundException;

/**
 * Factory for form GuildDescription
 *
 * @author Jakub Konečný
 */
class GuildDescriptionFormFactory extends BaseFormFactory {
  /** @var \HeroesofAbenez\Model\Guild */
  protected $model;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(\Nette\Localization\ITranslator $translator, \HeroesofAbenez\Model\Guild $model, \Nette\Security\User $user) {
    $this->model = $model;
    $this->user = $user;
    parent::__construct($translator);
  }
  
  public function create(): Form {
    $form = parent::createBase();
    $guild = $this->model->view($this->user->identity->guild);
    $form->addTextArea("description", "forms.guildDescription.descriptionField.label")
      ->setDefaultValue($guild->description);
    $form->addSubmit("change", "forms.guildDescription.changeButton.label");
    $form->onSuccess[] = [$this, "submitted"];
    return $form;
  }
  
  public function submitted(Form $form, array $values): void {
    $guild = $this->user->identity->guild;
    $description = $values["description"];
    try {
      $this->model->changeDescription($guild, $description);
    } catch(GuildNotFoundException $e) {
      $form->addError($this->translator->translate("errors.guild.doesNotExist"));
    }
  }
}
?>