<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * User to character mapper for production
 * Retrieves user id from website
 *
 * @author Jakub Konečný
 */
class ProductionUserToCharacterMapper implements IUserToCharacterMapper {
  public function getRealId(): int {
    $ch = curl_init("http://heroesofabenez.tk/auth.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $uid = curl_exec($ch);
    curl_close($ch);
    return $uid;
  }
}
?>