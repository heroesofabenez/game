<?php
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
    $guild = $this->model->guildData($this->user->identity->guild);
    $form->addTextArea("description", "forms.guildDescription.descriptionField.label")
         ->setDefaultValue($guild->description);
    $form->addSubmit("change", "forms.guildDescription.changeButton.label");
    $form->onSuccess[] = [$this, "submitted"];
    return $form;
  }
  
  /**
   * @param Form $form
   * @param array $values
   * @return void
   */
  function submitted(Form $form, array $values) {
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