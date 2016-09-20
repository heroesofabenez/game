<?php
namespace HeroesofAbenez\Presenters;

use Nette\Application\UI\Form,
    HeroesofAbenez\Chat;

/**
 * Presenter Tavern
 *
 * @author Jakub Konečný
 */
class TavernPresenter extends BasePresenter {
  /**
   * @return void
   */
  function startup() {
    parent::startup();
    $this->template->haveForm = true;
    $this->template->canChat = true;
  }
  
  /**
   * Use just one template for this presenter
   * 
   * @return array
   */
  function formatTemplateFiles() {
    return [APP_DIR . "/templates/Tavern.@layout.latte"];
  }
  
  /**
   * @return void
   */
  function actionGuild() {
    if($this->user->identity->guild === 0) $this->template->canChat = false;
    $this->template->title = "Guild chat";
    $this->template->chat = "guildChat";
  }
  
  /**
   * @return void
   */
  function actionLocal() {
    $this->template->title = "Local chat";
    $this->template->chat = "localChat";
  }
  
   /**
   * @return void
   */
  function actionGlobal() {
    $this->template->title = "Global chat";
    $this->template->chat = "globalChat";
  }
  
  /**
   * @return Chat\GuildChatControl
   */
  protected function createComponentGuildChat(Chat\GuildChatControlFactory $factory): Chat\GuildChatControl {
    return $factory->create();
  }
  
  /**
   * @return Chat\LocalChatControl
   */
  protected function createComponentLocalChat(Chat\LocalChatControlFactory $factory): Chat\LocalChatControl {
    return $factory->create();
  }
  
  /**
   * @return Chat\GlobalChatControl
   */
  protected function createComponentGlobalChat(Chat\GlobalChatControlFactory $factory): Chat\GlobalChatControl {
    return $factory->create();
  }
  
  /**
   * Creates form for writing new message
   * 
   * @return Form
   */
  protected function createComponentNewMessageForm(): Form {
    $form = new Form;
    $form->setTranslator($this->translator);
    $form->addText("message")
         ->setRequired("forms.tavernNewMessage.messageField.error");
    $form->addSubmit("send", "forms.tavernNewMessage.sendButton.label");
    $form->onSuccess[] = [$this, "newMessageSucceeded"];
    return $form;
  }
  
  /**
   *
   * @param Form $form
   * @param array $values
   * @return void
   */
  function newMessageSucceeded(Form $form, array $values) {
    switch($this->action) {
      case "guild":
        $factory = $this->context->getByType(Chat\GuildChatControlFactory::class);
        $chat = $this->createComponentGuildChat($factory);
  break;
      case "local":
        $factory = $this->context->getByType(Chat\LocalChatControlFactory::class);
        $chat = $this->createComponentLocalChat($factory);
  break;
      case "global":
        $factory = $this->context->getByType(Chat\GlobalChatControlFactory::class);
        $chat = $this->createComponentGlobalChat($factory);
  break;
    }
    $this->addComponent($chat, "chat");
    $chat->newMessage($values["message"]);
  }
}
?>