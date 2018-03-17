<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\ChatMessage,
    HeroesofAbenez\Orm\Character,
    Nextras\Orm\Collection\ICollection;

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
  /** @var ChatCommandsProcessor */
  protected $processor;
  /** @var string*/
  protected $textColumn;
  /** @var string */
  protected $characterColumn;
  /** @var int*/
  protected $textValue;
  /** @var int */
  protected $characterValue;
  /** @var array */
  protected $names = [];
  
  public function __construct(ORM $orm, \Nette\Security\User $user, ChatCommandsProcessor  $processor, string $textColumn, int $textValue, string $characterColumn = NULL, $characterValue = NULL) {
    parent::__construct();
    $this->orm = $orm;
    $this->user = $user;
    $this->processor = $processor;
    $this->textColumn = $textColumn;
    $this->characterColumn = $characterColumn ?? $textColumn;
    $this->textValue = $textValue;
    $this->characterValue = $characterValue ?? $textValue;
  }
  
  /**
   * Gets texts for the current chat
   * 
   * @return ICollection|ChatMessage[]
   */
  public function getTexts(): ICollection {
    $count = $this->orm->chatMessages->findBy([
      $this->textColumn => $this->textValue,
    ])->countStored();
    $paginator = new \Nette\Utils\Paginator;
    $paginator->setItemCount($count);
    $paginator->setItemsPerPage(25);
    $paginator->setPage($paginator->pageCount);
    return $this->orm->chatMessages->findBy([
      $this->textColumn => $this->textValue,
    ])->limitBy($paginator->length, $paginator->offset);
  }
  
  /**
   * Gets characters in the current chat
   * 
   * @return ICollection|Character[]
   */
  public function getCharacters(): ICollection {
    return $this->orm->characters->findBy([
      $this->characterColumn => $this->characterValue
    ]);
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
  
  /**
   * Submits new message
   */
  public function newMessage(string $message): void {
    $result = $this->processor->parse($message);
    if(!is_null($result)) {
      $this->presenter->flashMessage($result);
    } else {
      $chatMessage = new ChatMessage();
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