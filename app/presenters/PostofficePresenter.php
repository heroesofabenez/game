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
    $form->translator = $this->translator;
    $chars = $this->model->getRecipients();
    $form->addSelect("to", "forms.postOfficeNewMessage.toSelect.label", $chars)
         ->setPrompt("forms.postOfficeNewMessage.toSelect.prompt")
         ->setRequired("forms.postOfficeNewMessage.toSelect.error");
    $form->addText("subject", "forms.postOfficeNewMessage.subjectField.label")
         ->setRequired("forms.postOfficeNewMessage.subjectField.empty")
         ->addRule(\Nette\Forms\Form::MAX_LENGTH, "forms.postOfficeNewMessage.subjectField.error", 35);
    $form->addTextArea("message", "forms.postOfficeNewMessage.messageField.label")
         ->setRequired("forms.postOfficeNewMessage.messageField.error");
    $form->addSubmit("send", "forms.postOfficeNewMessage.sendButton.label");
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
    $this->model->sendMessage($data);
    $this->flashMessage($this->translator->translate("messages.postoffice.messageSent"));
    $this->redirect("Postoffice:sent");
  }
}
?>