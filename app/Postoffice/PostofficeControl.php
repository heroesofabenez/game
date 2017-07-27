<?php
declare(strict_types=1);

namespace HeroesofAbenez\Postoffice;

use HeroesofAbenez\Orm\Model as ORM,
    Nextras\Orm\Collection\ICollection,
    HeroesofAbenez\Orm\Message;

/**
 * Postoffice Control
 *
 * @author Jakub Konečný
 */
class PostofficeControl extends \Nette\Application\UI\Control {
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Model\Profile */
  protected $profileModel;
  
  public function __construct(ORM $orm, \Nette\Security\User $user, \HeroesofAbenez\Model\Profile $profileModel) {
    parent::__construct();
    $this->orm = $orm;
    $this->user = $user;
    $this->profileModel = $profileModel;
  }
  
  /**
   * Gets list of received messages
   * 
   * @return ICollection|Message[]
   */
  protected function getReceivedMessages(): ICollection {
    return $this->orm->messages->findByTo($this->user->id);
  }
  
  public function renderInbox(): void {
    $this->template->setFile(__DIR__ . "/postofficeInbox.latte");
    $this->template->messages = $this->getReceivedMessages();
    $this->template->render();
  }
  
  /**
   * Gets list of sent messages
   * 
   * @return ICollection|Message[]
   */
  protected function getSentMessages(): ICollection {
    return $this->orm->messages->findByFrom($this->user->id);
  }
  
  public function renderOutbox(): void {
    $this->template->setFile(__DIR__ . "/postofficeOutbox.latte");
    $this->template->messages = $this->getSentMessages();
    $this->template->render();
  }
  
  protected function canShow(Message $message): bool {
    if($message->from->id == $this->user->id OR $message->to->id == $this->user->id) {
      return true;
    } else {
      return false;
    }
  }
  
  public function messageStatus(int $id): int {
    $message = $this->orm->messages->getById($id);
    if(is_null($message)) {
      return 0;
    }
    if(!$this->canShow($message)) {
      return -1;
    }
    return 1;
  }
  
  /**
   * Show specified message
   *
   * @throws MessageNotFoundException
   * @throws CannotShowMessageException
   */
  public function message(int $id): Message {
    $message = $this->orm->messages->getById($id);
    if(is_null($message)) {
      throw new MessageNotFoundException;
    }
    if(!$this->canShow($message)) {
      throw new CannotShowMessageException;
    }
    return $message;
  }
  
  public function renderMessage(int $id): void {
    $this->template->setFile(__DIR__ . "/postofficeMessage.latte");
    try {
      $this->template->message = $this->message($id);
    } catch(CannotShowMessageException $e) {
      $this->presenter->forward("cannotshow");
    } catch(MessageNotFoundException $e) {
      $this->presenter->forward("notfound");
    }
    $this->template->render();
  }
  
  public function getRecipients(): array {
    return $this->orm->characters->findAll()
      ->orderBy("id")
      ->fetchPairs("id", "name");
  }
  
  public function sendMessage(array $data): void {
    $message = new Message;
    $this->orm->messages->attach($message);
    foreach($data as $key => $value) {
      $message->$key = $value;
    }
    $this->orm->messages->persistAndFlush($message);
  }
}
?>