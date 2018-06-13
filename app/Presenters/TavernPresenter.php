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
final class TavernPresenter extends BasePresenter {
  protected function startup(): void {
    parent::startup();
    $this->template->haveForm = true;
    $this->template->canChat = true;
  }
  
  /**
   * Use just one template for this presenter
   */
  public function formatTemplateFiles() {
    return [__DIR__ . "/../templates/Tavern.@layout.latte"];
  }
  
  public function actionGuild(): void {
    if($this->user->identity->guild === 0) {
      $this->template->canChat = false;
    }
    $this->template->title = "Guild chat";
    $this->template->chat = "guildChat";
  }
  
  public function actionLocal(): void {
    $this->template->title = "Local chat";
    $this->template->chat = "localChat";
  }
  
  public function actionGlobal(): void {
    $this->template->title = "Global chat";
    $this->template->chat = "globalChat";
  }
  
  protected function createComponentGuildChat(Chat\IGuildChatControlFactory $factory): Chat\GuildChatControl {
    return $factory->create();
  }
  
  protected function createComponentLocalChat(Chat\ILocalChatControlFactory $factory): Chat\LocalChatControl {
    return $factory->create();
  }
  
  protected function createComponentGlobalChat(Chat\IGlobalChatControlFactory $factory): Chat\GlobalChatControl {
    return $factory->create();
  }
  
  /**
   * Creates form for writing new message
   */
  protected function createComponentNewMessageForm(NewChatMessageFormFactory $factory): Form {
    switch($this->action) {
      case "guild":
        /** @var Chat\IGuildChatControlFactory $chatFactory */
        $chatFactory = $this->context->getByType(Chat\IGuildChatControlFactory::class);
        $chat = $this->createComponentGuildChat($chatFactory);
        break;
      case "local":
        /** @var Chat\ILocalChatControlFactory $chatFactory */
        $chatFactory = $this->context->getByType(Chat\ILocalChatControlFactory::class);
        $chat = $this->createComponentLocalChat($chatFactory);
        break;
      case "global":
        /** @var Chat\IGlobalChatControlFactory $chatFactory */
        $chatFactory = $this->context->getByType(Chat\IGlobalChatControlFactory::class);
        $chat = $this->createComponentGlobalChat($chatFactory);
        break;
    }
    return $factory->create($chat);
  }
}
?>