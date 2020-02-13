<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use HeroesofAbenez\Model\InsufficientFundsException;
use HeroesofAbenez\Orm\Model as ORM;
use Nette\Application\UI\Form;

final class DonateToGuildFormFactory extends BaseFormFactory {
  protected \HeroesofAbenez\Model\Guild $model;
  protected \Nette\Security\User $user;
  protected ORM $orm;

  public function __construct(\Nette\Localization\ITranslator $translator, \HeroesofAbenez\Model\Guild $model) {
    $this->model = $model;
    parent::__construct($translator);
  }

  public function create(): Form {
    $form = $this->createBase();
    $form->addInteger("amount", "forms.donateToGuild.amountField.label")
      ->setDefaultValue(0)
      ->setRequired("forms.donateToGuild.amountField.empty")
      ->addRule(Form::MIN, "forms.donateToGuild.amountField.errorTooLow", 1);
    $form->addSubmit("donate", "forms.donateToGuild.donateButton.label");
    $form->onSuccess[] = [$this, "process"];
    return $form;
  }

  public function process(Form $form, array $values): void {
    try {
      $this->model->donate($values["amount"]);
    } catch(InsufficientFundsException $e) {
      $form->addError("forms.donateToGuild.amountField.errorTooHigh");
    }
  }
}
?>