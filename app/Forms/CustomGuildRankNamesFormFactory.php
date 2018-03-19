<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form,
    HeroesofAbenez\Model\MissingPermissionsException;


/**
 * Factory for form CustomGuildRankNames
 *
 * @author Jakub Konečný
 */
class CustomGuildRankNamesFormFactory extends BaseFormFactory {
  /** @var  \HeroesofAbenez\Model\Guild */
  protected $model;
  /** @var  \Nette\Security\User */
  protected $user;
  
  public function __construct(\Nette\Localization\ITranslator $translator, \HeroesofAbenez\Model\Guild $model, \Nette\Security\User $user) {
    parent::__construct($translator);
    $this->model = $model;
    $this->user = $user;
  }
  
  public function create(): Form {
    $form = $this->createBase();
    $defaults = $this->model->getDefaultRankNames();
    $custom = $this->model->getCustomRankNames($this->user->identity->guild);
    for($i = 1; $i <= count($defaults); $i++) {
      $fieldName = "rank{$i}name";
      $form->addText($fieldName, "guildranks.$i.name");
      if(isset($custom[$i])) {
        $form[$fieldName]->value = $custom[$i];
      }
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