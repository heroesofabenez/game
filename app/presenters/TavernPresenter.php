<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Chat;
use Nette\Application\UI;

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
    return array(APP_DIR . "/templates/Tavern.@layout.latte");
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
  protected function createComponentGuildChat() {
    return $this->context->getService("chat.guild");
  }
  
  /**
   * @return \HeroesofAbenez\Chat\LocalChatControl
   */
  protected function createComponentLocalChat() {
    return $this->context->getService("chat.local");
  }
  
  /**
   * @return \HeroesofAbenez\Chat\GlobalChatControl
   */
  protected function createComponentGlobalChat() {
    return $this->context->getService("chat.global");
  }
  
  /**
   * Creates form for writting new message
   * 
   * @return \Nette\Application\UI\Form
   */
  protected function createComponentNewMessageForm() {
    $form = new UI\Form;
    $form->addText("message")
         ->setRequired("Enter message.");
    $form->addSubmit("send", "Send");
    $form->onSuccess[] = array($this, "newMessageSucceeded");
    return $form;
  }
  
  /**
   * 
   * @param \Nette\Application\UI\Form $form
   * @param \ Nette\Utils\ArrayHash $values
   * @return void
   */
  function newMessageSucceeded(UI\Form $form, $values) {
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
    $chat->newMessage($this->user->id, $values["message"]);
  }
}
?>