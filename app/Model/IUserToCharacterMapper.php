<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * IUserToCharacterMapper
 * Is responsible for mapping user to character (and vice versa)
 * Is used during authentication and registration
 *
 * @author Jakub Konečný
 */
interface IUserToCharacterMapper {
  public function getRealId(): int;
}
?>