<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use HeroesofAbenez\Model\Guild;
use Nette\Application\UI\Form;
use HeroesofAbenez\Model\GuildNotFoundException;
use Nette\Localization\Translator;

/**
 * Factory for form GuildDescription
 *
 * @author Jakub Konečný
 */
final class GuildDescriptionFormFactory extends BaseFormFactory {
  public function __construct(Translator $translator, private readonly Guild $model, private readonly \Nette\Security\User $user) {
    parent::__construct($translator);
  }
  
  public function create(): Form {
    $form = $this->createBase();
    /** @var \HeroesofAbenez\Orm\Guild $guild */
    $guild = $this->model->view($this->user->identity->guild);
    $form->addTextArea("description", "forms.guildDescription.descriptionField.label")
      ->setDefaultValue($guild->description);
    $form->addSubmit("change", "forms.guildDescription.changeButton.label");
    $form->onSuccess[] = [$this, "process"];
    return $form;
  }
  
  public function process(Form $form, array $values): void {
    $guild = $this->user->identity->guild;
    $description = $values["description"];
    try {
      $this->model->changeDescription($guild, $description);
    } catch(GuildNotFoundException) {
      $form->addError($this->translator->translate("errors.guild.doesNotExist"));
    }
  }
}
?>