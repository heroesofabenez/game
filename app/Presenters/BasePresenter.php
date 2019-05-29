<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Nexendrie\Menu\IMenuControlFactory;
use Nexendrie\Menu\MenuControl;

  /**
   * Parent of all presenters
   * 
   * @author Jakub Konečný
   */
abstract class BasePresenter extends \Nette\Application\UI\Presenter {
  /** @var \Nette\Localization\ITranslator */
  protected $translator;
  /** @var \HeroesofAbenez\Model\SettingsRepository */
  protected $sr;
  
  use \Kdyby\Autowired\AutowireComponentFactories;
  
  public function injectTranslator(\Nette\Localization\ITranslator $translator): void {
    $this->translator = $translator;
  }
  
  public function injectSettingsRepository(\HeroesofAbenez\Model\SettingsRepository $sr): void {
    $this->sr = $sr;
  }
  
  /**
   * Login user and set server number for template
   */
  protected function startup(): void {
    parent::startup();
    $this->tryLogin();
    $this->template->server = $this->sr->settings["application"]["server"];
  }
  
  /**
   * Try to login the user
   */
  public function tryLogin(): void {
    if(!$this->user->isLoggedIn()) {
      $this->user->login("");
    }
    $uid = $this->user->id;
    if($this instanceof CharacterPresenter AND $uid === -1) {
      return;
    }
    if($this instanceof CharacterPresenter AND is_null($this->user->identity->stage)) {
      return;
    }
    if($this instanceof CharacterPresenter AND $uid > 0) {
      $this->redirect(301, "Homepage:default");
    }
    if($this instanceof IntroPresenter AND is_null($this->user->identity->stage)) {
      return;
    }
    switch($uid) {
      case -1:
        $this->redirect(302, "Character:create");
        break;
      case 0:
        $this->redirectUrl("http://heroesofabenez.tk/");
    }
    if(is_null($this->user->identity->stage)) {
      $this->redirect(302, "Intro:default");
    }
  }
  
  protected function createComponentMenu(IMenuControlFactory $factory): MenuControl {
    return $factory->create();
  }

  /**
   * @param string $message
   * @param string $type
   */
  public function flashMessage($message, $type = "info"): \stdClass {
    $message = $this->translator->translate($message);
    return parent::flashMessage($message, $type);
  }
}
?>