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
   * @return \HeroesofAbenez\Chat\GuildChatControl
   */
  protected function createComponentGuildChat(Chat\GuildChatControlFactory $factory) {
    return $factory->create();
  }
  
  /**
   * @return \HeroesofAbenez\Chat\LocalChatControl
   */
  protected function createComponentLocalChat(Chat\LocalChatControlFactory $factory) {
    return $factory->create();
  }
  
  /**
   * @return \HeroesofAbenez\Chat\GlobalChatControl
   */
  protected function createComponentGlobalChat(Chat\GlobalChatControlFactory $factory) {
    return $factory->create();
  }
  
  /**
   * Creates form for writting new message
   * 
   * @return \Nette\Application\UI\Form
   */
  protected function createComponentNewMessageForm() {
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
   * @param \Nette\Application\UI\Form $form
   * @param \ Nette\Utils\ArrayHash $values
   * @return void
   */
  function newMessageSucceeded(Form $form, $values) {
    switch($this->action) {
case "guild":
  $chat = $this->createComponentGuildChat();
  break;
case "local":
  $chat = $this->createComponentLocalChat();
  break;
case "global":
  $chat = $this->createComponentGlobalChat();
  break;
    }
    $chat->create()->newMessage($values["message"]);
  }
}
?>