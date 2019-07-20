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
  /** @var IMenuControlFactory */
  protected $menuFactory;
  
  public function injectTranslator(\Nette\Localization\ITranslator $translator): void {
    $this->translator = $translator;
  }
  
  public function injectSettingsRepository(\HeroesofAbenez\Model\SettingsRepository $sr): void {
    $this->sr = $sr;
  }

  public function injectMenuFactory(IMenuControlFactory $menuFactory): void {
    $this->menuFactory = $menuFactory;
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
    if($this instanceof CharacterPresenter && $uid === -1) {
      return;
    }
    if($this instanceof CharacterPresenter && $this->user->identity->stage === null) {
      return;
    }
    if($this instanceof CharacterPresenter && $uid > 0) {
      $this->redirectPermanent("Homepage:default");
    }
    if($this instanceof IntroPresenter && $this->user->identity->stage === null) {
      return;
    }
    switch($uid) {
      case -1:
        $this->redirect("Character:create");
        break;
      case 0:
        $this->redirectUrl("http://heroesofabenez.tk/");
    }
    if($this->user->identity->stage === null) {
      $this->redirect("Intro:default");
    }
  }
  
  protected function createComponentMenu(): MenuControl {
    return $this->menuFactory->create();
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