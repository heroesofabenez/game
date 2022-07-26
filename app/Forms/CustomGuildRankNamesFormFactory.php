<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form;
use HeroesofAbenez\Model\MissingPermissionsException;

/**
 * Factory for form CustomGuildRankNames
 *
 * @author Jakub Konečný
 */
final class CustomGuildRankNamesFormFactory extends BaseFormFactory {
  private \HeroesofAbenez\Model\Guild $model;
  private \Nette\Security\User $user;

  public function __construct(\Nette\Localization\ITranslator $translator, \HeroesofAbenez\Model\Guild $model, \Nette\Security\User $user) {
    parent::__construct($translator);
    $this->model = $model;
    $this->user = $user;
  }
  
  public function create(): Form {
    $form = $this->createBase();
    $defaults = $this->model->getDefaultRankNames();
    $custom = $this->model->getCustomRankNames($this->user->identity->guild);
    $defaultsCount = count($defaults);
    for($i = 1; $i <= $defaultsCount; $i++) {
      $fieldName = "rank{$i}name";
      $form->addText($fieldName, "guildranks.$i.name")->value = $custom[$i] ?? "";
    }
    $form->addSubmit("submit", "forms.customGuildRankNames.sendButton.label");
    $form->onSuccess[] = [$this, "process"];
    return $form;
  }
  
  public function process(Form $form, array $values): void {
    try {
      $this->model->setCustomRankNames($values);
    } catch(MissingPermissionsException $e) {
      $form->addError($this->translator->translate("errors.guild.missingPermissions"));
    }
  }
}
?>