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
final class NextrasOrmAdapter implements DatabaseAdapter
{
    public function __construct(private readonly ORM $orm, private readonly \Nette\Security\User $user)
    {
    }

    public function getTexts(string $column, mixed $value, int $limit): ChatMessagesCollection
    {
        $count = $this->orm->chatMessages->findBy([
            $column => $value,
        ])->countStored();
        $paginator = new \Nette\Utils\Paginator();
        $paginator->setItemCount($count);
        $paginator->setItemsPerPage($limit);
        $paginator->setPage($paginator->pageCount ?? 0);
        $messages = $this->orm->chatMessages->findBy([
            $column => $value,
        ])->limitBy($paginator->length, $paginator->offset);
        $collection = new ChatMessagesCollection();
        foreach ($messages as $message) {
            $character = new ChatCharacter($message->character->id, $message->character->name);
            $collection[] = new ChatMessage($message->id, $message->message, $message->whenS, $character);
        }
        return $collection;
    }

    public function getCharacters(string $column, mixed $value): ChatCharactersCollection
    {
        /** @var \HeroesofAbenez\Orm\Character $character */
        $character = $this->orm->characters->getById($this->user->id);
        $character->lastActive = new \DateTimeImmutable();
        $this->orm->characters->persistAndFlush($character);
        unset($character);
        $characters = $this->orm->characters->findBy([
            $column => $value, "lastActive>=" => new \DateTimeImmutable("5 minutes ago")
        ]);
        $collection = new ChatCharactersCollection();
        foreach ($characters as $character) {
            $collection[] = new ChatCharacter($character->id, $character->name);
        }
        return $collection;
    }

    public function addMessage(string $message, string $filterColumn, int $filterValue): void
    {
        $chatMessage = new ChatMessageEntity();
        $chatMessage->message = $message;
        $this->orm->chatMessages->attach($chatMessage);
        $chatMessage->character = $this->user->id;
        $chatMessage->{$filterColumn} = $filterValue;
        $this->orm->chatMessages->persistAndFlush($chatMessage);
    }
}
