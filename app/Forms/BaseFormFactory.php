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
  public function __construct(protected readonly Translator $translator) {
  }
  
  public function createBase(): Form {
    $form = new Form();
    $form->setTranslator($this->translator);
    return $form;
  }
}
?>