<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Nette\Application\UI\Form,
    HeroesofAbenez\Chat;

/**
 * Presenter Tavern
 *
 * @author Jakub Konečný
 */
class TavernPresenter extends BasePresenter {
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
  protected function createComponentNewMessageForm(): Form {
    $form = new Form();
    $form->setTranslator($this->translator);
    $form->addText("message")
      ->setRequired("forms.tavernNewMessage.messageField.error");
    $form->addSubmit("send", "forms.tavernNewMessage.sendButton.label");
    $form->onSuccess[] = [$this, "newMessageSucceeded"];
    return $form;
  }
  
  public function newMessageSucceeded(Form $form, array $values): void {
    switch($this->action) {
      case "guild":
        /** @var Chat\IGuildChatControlFactory $factory */
        $factory = $this->context->getByType(Chat\IGuildChatControlFactory::class);
        $chat = $this->createComponentGuildChat($factory);
        break;
      case "local":
        /** @var Chat\ILocalChatControlFactory $factory */
        $factory = $this->context->getByType(Chat\ILocalChatControlFactory::class);
        $chat = $this->createComponentLocalChat($factory);
        break;
      case "global":
        /** @var Chat\IGlobalChatControlFactory $factory */
        $factory = $this->context->getByType(Chat\IGlobalChatControlFactory::class);
        $chat = $this->createComponentGlobalChat($factory);
        break;
    }
    $this->addComponent($chat, "chat");
    $chat->newMessage($values["message"]);
  }
}
?>