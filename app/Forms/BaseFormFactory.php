<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form;
use Nette\Localization\Translator;

/**
 * BaseFormFactory
 *
 * @author Jakub Konečný
 */
abstract class BaseFormFactory {
  protected Translator $translator;
  
  public function __construct(Translator $translator) {
    $this->translator = $translator;
  }
  
  public function createBase(): Form {
    $form = new Form();
    $form->setTranslator($this->translator);
    return $form;
  }
}
?>