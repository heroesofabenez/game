<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * IDatabaseAdapter
 *
 * @author Jakub Konečný
 */
interface IDatabaseAdapter {
  public function getTexts(string $column, $value, int $limit): ChatMessagesCollection;
  public function getCharacters(string $column, $value): ChatCharactersCollection;
  public function addMessage(string $message, string $filterColumn, int $filterValue): void;
}
?>