<?php
declare(strict_types=1);

namespace HeroesofAbenez\Forms;

use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

/**
 * BaseFormFactory
 *
 * @author Jakub Konečný
 */
abstract class BaseFormFactory {
  /** @var ITranslator */
  protected $translator;
  
  public function __construct(ITranslator $translator) {
    $this->translator = $translator;
  }
  
  public function createBase(): Form {
    $form = new Form();
    $form->setTranslator($this->translator);
    return $form;
  }
}
?>