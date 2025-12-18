<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\UserToCharacterMapper;
use Nette\Localization\Translator;
use Nexendrie\Menu\IMenuControlFactory;
use Nexendrie\Menu\MenuControl;

/**
 * Parent of all presenters
 *
 * @author Jakub Konečný
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
    protected Translator $translator;
    protected \HeroesofAbenez\Model\SettingsRepository $sr;
    protected IMenuControlFactory $menuFactory;

    public function injectTranslator(Translator $translator): void
    {
        $this->translator = $translator;
    }

    public function injectSettingsRepository(\HeroesofAbenez\Model\SettingsRepository $sr): void
    {
        $this->sr = $sr;
    }

    public function injectMenuFactory(IMenuControlFactory $menuFactory): void
    {
        $this->menuFactory = $menuFactory;
    }

    /**
     * Login user and set server number for template
     */
    protected function startup(): void
    {
        parent::startup();
        $this->tryLogin();
        $this->template->server = $this->sr->settings["application"]["server"];
    }

    /**
     * Try to login the user
     */
    public function tryLogin(): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->user->login("");
        }
        $uid = $this->user->id;
        if ($this instanceof CharacterPresenter && $uid === UserToCharacterMapper::USER_ID_NO_CHARACTER) {
            return;
        }
        if ($this instanceof CharacterPresenter && $this->user->identity->stage === null) {
            return;
        }
        if ($this instanceof CharacterPresenter && $uid > UserToCharacterMapper::USER_ID_NOT_LOGGED_IN) {
            $this->redirectPermanent("Homepage:default");
        }
        if ($this instanceof IntroPresenter && $this->user->identity->stage === null) {
            return;
        }
        switch ($uid) {
            case UserToCharacterMapper::USER_ID_NO_CHARACTER:
                $this->redirect("Character:create");
                break; // @phpstan-ignore deadCode.unreachable
            case UserToCharacterMapper::USER_ID_NOT_LOGGED_IN:
                $this->redirectUrl("http://heroesofabenez.tk/");
                break; // @phpstan-ignore deadCode.unreachable
        }
        if ($this->user->identity->stage === null) {
            $this->redirect("Intro:default");
        }
    }

    protected function createComponentMenu(): MenuControl
    {
        return $this->menuFactory->create();
    }

    /**
     * @param mixed $message
     */
    public function flashMessage($message, string $type = "info"): \stdClass
    {
        $message = $this->translator->translate((string) $message);
        return parent::flashMessage($message, $type);
    }

    protected function reloadIdentity(): void
    {
        $this->user->logout();
    }
}
