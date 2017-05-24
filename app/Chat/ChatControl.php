<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\ChatMessage,
    Nextras\Orm\Collection\ICollection;

/**
 * Basic Chat Control
 *
 * @author Jakub Konečný
 */
abstract class ChatControl extends \Nette\Application\UI\Control {
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ChatCommandsProcessor */
  protected $processor;
  /** @var string*/
  protected $table;
  /** @var string*/
  protected $param;
  /** @var string */
  protected $param2;
  /** @var int*/
  protected $id;
  /** @var int */
  protected $id2;
  /** @var array */
  protected $names = [];
  
  function __construct(ORM $orm, \Nette\Security\User $user, ChatCommandsProcessor  $processor, string $param, int $id, string $param2 = NULL, $id2 = NULL) {
    parent::__construct();
    $this->orm = $orm;
    $this->user = $user;
    $this->processor = $processor;
    $this->param = $param;
    $this->param2 = $param2 ?? $param;
    $this->id = $id;
    $this->id2 = $id2 ?? $id;
  }
  
  /**
   * Gets texts for the current chat
   * 
   * @return ICollection|ChatMessage[]
   */
  function getTexts(): ICollection {
    $count = $this->orm->chatMessages->findBy([
      $this->param => $this->id,
    ])->countStored();
    $paginator = new \Nette\Utils\Paginator;
    $paginator->setItemCount($count);
    $paginator->setItemsPerPage(25);
    $paginator->setPage($paginator->pageCount);
    return $this->orm->chatMessages->findBy([
      $this->param => $this->id,
    ])->limitBy($paginator->length, $paginator->offset);
  }
  
  /**
   * Gets characters in the current chat
   * 
   * @return ICollection|ChatMessage[]
   */
  function getCharacters(): ICollection {
    return $this->orm->characters->findBy([
      $this->param2 => $this->id2
    ]);
  }
  
  /**
   * Renders the chat
   * 
   * @return void
   */
  function render(): void {
    $this->template->setFile(__DIR__ . "/chat.latte");
    $this->template->characters = $this->getCharacters();
    $this->template->texts = $this->getTexts();
    $this->template->render();
  }
  
  /**
   * Submits new message
   * 
   * @param string $message message
   * @return void
   */
  function newMessage(string $message): void {
    $result = $this->processor->parse($message);
    if($result) {
      $this->presenter->flashMessage($result);
    } else {
      $chatMessage = new ChatMessage;
      $chatMessage->message = $message;
      $this->orm->chatMessages->attach($chatMessage);
      $chatMessage->character = $this->user->id;
      $chatMessage->{$this->param} = $this->id;
      $this->orm->chatMessages->persistAndFlush($chatMessage);
    }
    $this->presenter->redirect("this");
  }
}
?>