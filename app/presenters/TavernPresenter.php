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
  }
  
  /**
   * @return void
   */
  function actionGuild() {
    if($this->user->identity->guild === 0) $this->template->canChat = false;
    else $this->template->canChat = true;
    $this->template->title = "Guild chat";
    $this->template->chat = "guildChat";
  }
  
  /**
   * @return void
   */
  function actionLocal() {
    $this->template->canChat = true;
    $this->template->title = "Local chat";
    $this->template->chat = "localChat";
  }
  
  /**
   * @return \HeroesofAbenez\Chat\GuildChatControl
   */
  protected function createComponentGuildChat() {
    $db = $this->context->getService("database.default.context");
    $gid = $this->user->identity->guild;
    return new Chat\GuildChatControl($db, $gid);
  }
  
  /**
   * @return \HeroesofAbenez\Chat\LocalChatControl
   */
  protected function createComponentLocalChat() {
    $db = $this->context->getService("database.default.context");
    $stage = $this->user->identity->stage;
    return new Chat\LocalChatControl($db, $stage);
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
    $db = $this->context->getService("database.default.context");
    switch($this->action) {
case "guild":
  $gid = $this->user->identity->guild;
  $chat = new Chat\GuildChatControl($db, $gid);
  break;
case "local":
  $stage = $this->user->identity->stage;
  $chat = new Chat\LocalChatControl($db, $stage);
  break;
    }
    $chat->newMessage($this->user->id, $values["message"]);
  }
}
?>