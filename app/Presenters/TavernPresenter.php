<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Nette\Application\UI\Form;
use HeroesofAbenez\Chat;
use HeroesofAbenez\Chat\NewChatMessageFormFactory;

/**
 * Presenter Tavern
 *
 * @author Jakub Konečný
 */
final class TavernPresenter extends BasePresenter
{
    private Chat\IGuildChatControlFactory $guildChatFactory;
    private Chat\ILocalChatControlFactory $localChatFactory;
    private Chat\IGlobalChatControlFactory $globalChatFactory;
    private NewChatMessageFormFactory $newChatMessageFormFactory;

    public function injectGuildChatFactory(Chat\IGuildChatControlFactory $guildChatFactory): void
    {
        $this->guildChatFactory = $guildChatFactory;
    }

    public function injectLocalChatFactory(Chat\ILocalChatControlFactory $localChatFactory): void
    {
        $this->localChatFactory = $localChatFactory;
    }

    public function injectGlobalChatFactory(Chat\IGlobalChatControlFactory $globalChatFactory): void
    {
        $this->globalChatFactory = $globalChatFactory;
    }

    public function injectNewChatMessageFormFactory(NewChatMessageFormFactory $newChatMessageFormFactory): void
    {
        $this->newChatMessageFormFactory = $newChatMessageFormFactory;
    }

    protected function startup(): void
    {
        parent::startup();
        $this->template->haveForm = true;
        $this->template->canChat = true;
    }

    /**
     * Use just one template for this presenter
     */
    public function formatTemplateFiles(): array
    {
        return [__DIR__ . "/../templates/Tavern.@layout.latte"];
    }

    public function actionGuild(): void
    {
        if ($this->user->identity->guild === 0) {
            $this->template->canChat = false;
        }
        $this->template->title = $this->translator->translate("texts.tavern.guildChat");
        $this->template->chat = "guildChat";
    }

    public function actionLocal(): void
    {
        $this->template->title = $this->translator->translate("texts.tavern.localChat");
        $this->template->chat = "localChat";
    }

    public function actionGlobal(): void
    {
        $this->template->title = $this->translator->translate("texts.tavern.globalChat");
        $this->template->chat = "globalChat";
    }

    protected function createComponentGuildChat(): Chat\GuildChatControl
    {
        return $this->guildChatFactory->create();
    }

    protected function createComponentLocalChat(): Chat\LocalChatControl
    {
        return $this->localChatFactory->create();
    }

    protected function createComponentGlobalChat(): Chat\GlobalChatControl
    {
        return $this->globalChatFactory->create();
    }

    /**
     * Creates form for writing new message
     *
     * @throws \Nette\Application\BadRequestException
     */
    protected function createComponentNewMessageForm(): Form
    {
        $chat = match ($this->action) {
            "guild" => $this->createComponentGuildChat(),
            "local" => $this->createComponentLocalChat(),
            "global" => $this->createComponentGlobalChat(),
            default => throw new \Nette\Application\BadRequestException(),
        };
        return $this->newChatMessageFormFactory->create($chat);
    }
}
