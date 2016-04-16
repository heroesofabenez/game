<?php
namespace HeroesofAbenez\Model;

use Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;

/**
 * Router Factory
 *
 * @author Jakub Konečný
 */
class RouterFactory extends \Nette\Object {
  /**
   * @return \Nette\Application\Routers\RouteList
   */
  static function create() {
    $router = new RouteList;
    $router[] = new Route("ranking[/<action>][/<page=1 [0-9]+>]", "Ranking:characters");
    $router[] = new Route("<presenter map|tavern>[/<action=local>]");
    $router[] = new Route("postoffice", "Postoffice:received");
    $router[] = new Route("<presenter>/<id [0-9]+>", "Homepage:view");
    $router[] = new Route("<presenter=Homepage>[/<action=default>][/<id>]");
    return $router;
  }
}
?>