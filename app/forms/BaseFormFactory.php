<?php
namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form;

/**
 * BaseFormFactory
 *
 * @author Jakub Konečný
 */
abstract class BaseFormFactory {
  /** @var \Nette\Localization\ITranslator */
  protected $translator;
  
  function __construct(\Nette\Localization\ITranslator $translator) {
    $this->translator = $translator;
  }
  
  /**
   * @return Form
   */
  function createBase() {
    $form = new Form;
    $form->setTranslator($this->translator);
    return $form;
  }
}
?>