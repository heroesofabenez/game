<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\ChatMessage as ChatMessageEntity;

/**
 * Basic Chat Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 */
abstract class ChatControl extends \Nette\Application\UI\Control {
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var IChatMessageProcessor[] */
  protected $messageProcessors = [];
  /** @var string*/
  protected $textColumn;
  /** @var string */
  protected $characterColumn;
  /** @var int*/
  protected $textValue;
  /** @var int */
  protected $characterValue;
  
  public function __construct(ORM $orm, \Nette\Security\User $user,  string $textColumn, int $textValue, string $characterColumn = NULL, $characterValue = NULL) {
    parent::__construct();
    $this->orm = $orm;
    $this->user = $user;
    $this->textColumn = $textColumn;
    $this->characterColumn = $characterColumn ?? $textColumn;
    $this->textValue = $textValue;
    $this->characterValue = $characterValue ?? $textValue;
  }
  
  public function addMessageProcessor(IChatMessageProcessor $processor): void {
    $this->messageProcessors[] = $processor;
  }
  
  /**
   * Gets texts for the current chat
   */
  public function getTexts(): ChatMessagesCollection {
    $count = $this->orm->chatMessages->findBy([
      $this->textColumn => $this->textValue,
    ])->countStored();
    $paginator = new \Nette\Utils\Paginator;
    $paginator->setItemCount($count);
    $paginator->setItemsPerPage(25);
    $paginator->setPage($paginator->pageCount);
    $messages = $this->orm->chatMessages->findBy([
      $this->textColumn => $this->textValue,
    ])->limitBy($paginator->length, $paginator->offset);
    $collection = new ChatMessagesCollection();
    foreach($messages as $message) {
      $character = new ChatCharacter($message->character->id, $message->character->name);
      $collection[] = new ChatMessage($message->id, $message->message, $message->whenS, $character);
    }
    return $collection;
  }
  
  /**
   * Gets characters in the current chat
   */
  public function getCharacters(): ChatCharactersCollection {
    $characters = $this->orm->characters->findBy([
      $this->characterColumn => $this->characterValue
    ]);
    $collection = new ChatCharactersCollection();
    foreach($characters as $character) {
      $collection[] = new ChatCharacter($character->id, $character->name);
    }
    return $collection;
  }
  
  /**
   * Renders the chat
   */
  public function render(): void {
    $this->template->setFile(__DIR__ . "/chat.latte");
    $this->template->characters = $this->getCharacters();
    $this->template->texts = $this->getTexts();
    $this->template->render();
  }
  
  protected function processMessage(string $message): ?string {
    foreach($this->messageProcessors as $processor) {
      $result = $processor->parse($message);
      if(is_string($result)) {
        return $result;
      }
    }
    return NULL;
  }
  
  /**
   * Submits new message
   */
  public function newMessage(string $message): void {
    $result = $this->processMessage($message);
    if(!is_null($result)) {
      $this->presenter->flashMessage($result);
    } else {
      $chatMessage = new ChatMessageEntity();
      $chatMessage->message = $message;
      $this->orm->chatMessages->attach($chatMessage);
      $chatMessage->character = $this->user->id;
      $chatMessage->{$this->textColumn} = $this->textValue;
      $this->orm->chatMessages->persistAndFlush($chatMessage);
    }
    $this->presenter->redirect("this");
  }
}
?>