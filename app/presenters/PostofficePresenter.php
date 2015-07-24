<?php
namespace HeroesofAbenez\Presenters;

use Nette\Application\UI;

/**
 * Presenter Postoffice
 *
 * @author Jakub Konečný
 */
class PostofficePresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\PostOffice @autowire */
  protected $model;
  
  /**
   * @return void
   */
  function renderReceived() {
    $this->template->messages = $this->model->inbox();
  }
  
  /**
   * @return void
   */
  function renderSent() {
    $this->template->messages = $this->model->sent();
  }
  
  /**
   * @return void
   */
  function renderNew() {
    $this->template->haveForm = true;
  }
  
  /**
   * @param int $id 
   * @return void
   */
  function actionMessage($id) {
    try {
      $message = $this->model->message($id);
      foreach($message as $key => $value) {
       $this->template->$key = $value;
      }
    } catch(\Nette\Application\ForbiddenRequestException $e) {
      $this->forward("cannotshow");
    } catch(\Nette\Application\BadRequestException $e) {
      $this->forward("notfound");
    }
  }
  
  /**
   * Creates form for writting new message
   * 
   * @return \Nette\Application\UI\Form
   */
  protected function createComponentNewMessageForm() {
    $form = new UI\Form;
    $chars = $this->model->getRecipients();
    $form->addSelect("to", "To:", $chars)
         ->setPrompt("Select recipient");
    $form->addText("subject", "Subject")
         ->setRequired("Enter subject.")
         ->addRule(\Nette\Forms\Form::MAX_LENGTH, "Subject can have no more than 35 letters", 35);
    $form->addTextArea("message", "Message:")
         ->setRequired("Enter message.");
    $form->addSubmit("send", "Send");
    $form->onSuccess[] = array($this, "newMessageFormSucceeded");
    return $form;
  }
  
  /**
   * 
   * @param \Nette\Application\UI\Form $form
   * @param \ Nette\Utils\ArrayHash $values
   * @return void
   */
  function newMessageFormSucceeded(UI\Form $form, $values) {
    $data = array(
      "from" => $this->user->id, "to" => $values["to"], "subject" => $values["subject"], "text" => $values["message"]
    );
    $result = $this->model->sendMessage($data);
    if($result) {
      $this->flashMessage($this->translator->translate("messages.postoffice.messageSent"));
      $this->redirect("Postoffice:sent");
    } else {
      $this->flashMessage("An error occured.");
    }
  }
}
?>