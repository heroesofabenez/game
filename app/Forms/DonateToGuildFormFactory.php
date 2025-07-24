<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use HeroesofAbenez\Model\Guild;
use HeroesofAbenez\Model\InsufficientFundsException;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;

final class DonateToGuildFormFactory extends BaseFormFactory {
  public function __construct(Translator $translator, private readonly Guild $model) {
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
    } catch(InsufficientFundsException) {
      $form->addError("forms.donateToGuild.amountField.errorTooHigh");
    }
  }
}
?>