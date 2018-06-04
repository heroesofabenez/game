<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\ChatMessage as ChatMessageEntity;

/**
 * NextrasOrmAdapter
 *
 * @author Jakub Konečný
 */
final class NextrasOrmAdapter implements IDatabaseAdapter {
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(ORM $orm, \Nette\Security\User $user) {
    $this->orm = $orm;
    $this->user = $user;
  }
  
  public function getTexts(string $column, $value, int $limit): ChatMessagesCollection {
    $count = $this->orm->chatMessages->findBy([
      $column => $value,
    ])->countStored();
    $paginator = new \Nette\Utils\Paginator;
    $paginator->setItemCount($count);
    $paginator->setItemsPerPage($limit);
    $paginator->setPage($paginator->pageCount);
    $messages = $this->orm->chatMessages->findBy([
      $column => $value,
    ])->limitBy($paginator->length, $paginator->offset);
    $collection = new ChatMessagesCollection();
    foreach($messages as $message) {
      $character = new ChatCharacter($message->character->id, $message->character->name);
      $collection[] = new ChatMessage($message->id, $message->message, $message->whenS, $character);
    }
    return $collection;
  }
  
  public function getCharacters(string $column, $value): ChatCharactersCollection {
    $characters = $this->orm->characters->findBy([
      $column => $value
    ]);
    $collection = new ChatCharactersCollection();
    foreach($characters as $character) {
      $collection[] = new ChatCharacter($character->id, $character->name);
    }
    return $collection;
  }
  
  public function addMessage(string $message, string $filterColumn, int $filterValue): void {
    $chatMessage = new ChatMessageEntity();
    $chatMessage->message = $message;
    $this->orm->chatMessages->attach($chatMessage);
    $chatMessage->character = $this->user->id;
    $chatMessage->{$filterColumn} = $filterValue;
    $this->orm->chatMessages->persistAndFlush($chatMessage);
  }
}
?>