<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Nette\Application\UI\Form,
    HeroesofAbenez\Postoffice;

/**
 * Presenter Postoffice
 *
 * @author Jakub Konečný
 */
class PostofficePresenter extends BasePresenter {
  /** @var Postoffice\PostofficeControlFactory @autowire */
  protected $poFactory;
  
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
  function actionMessage(int $id) {
    $status = $this->createComponentPostoffice()->messageStatus($id);
    if($status === 0) {
      $this->forward("notfound");
    } elseif($status === -1) {
      $this->forward("cannotshow");
    }
    $this->template->id = $id;
  }
  
  /**
   * @return Postoffice\PostofficeControl
   */
  protected function createComponentPostoffice(): Postoffice\PostofficeControl {
    return $this->poFactory->create();
  }
  
  /**
   * Creates form for writing new message
   * 
   * @return Form
   */
  protected function createComponentNewMessageForm(): Form {
    $form = new Form;
    $form->setTranslator($this->translator);
    $chars = $this->createComponentPostoffice()->getRecipients();
    $form->addSelect("to", "forms.postOfficeNewMessage.toSelect.label", $chars)
      ->setPrompt("forms.postOfficeNewMessage.toSelect.prompt")
      ->setRequired("forms.postOfficeNewMessage.toSelect.error");
    $form->addText("subject", "forms.postOfficeNewMessage.subjectField.label")
      ->setRequired("forms.postOfficeNewMessage.subjectField.empty")
      ->addRule(Form::MAX_LENGTH, "forms.postOfficeNewMessage.subjectField.error", 35);
    $form->addTextArea("message", "forms.postOfficeNewMessage.messageField.label")
      ->setRequired("forms.postOfficeNewMessage.messageField.error");
    $form->addSubmit("send", "forms.postOfficeNewMessage.sendButton.label");
    $form->onSuccess[] = [$this, "newMessageFormSucceeded"];
    return $form;
  }
  
  /**
   * 
   * @param Form $form
   * @param array $values
   * @return void
   */
  function newMessageFormSucceeded(Form $form, array $values) {
    $data = [
      "from" => $this->user->id, "to" => $values["to"], "subject" => $values["subject"], "text" => $values["message"]
    ];
    $this->createComponentPostoffice()->sendMessage($data);
    $this->flashMessage($this->translator->translate("messages.postoffice.messageSent"));
    $this->redirect("Postoffice:sent");
  }
}
?>