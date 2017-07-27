<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Application\Routers\RouteList,
    Nette\Application\Routers\Route;

/**
 * Router Factory
 *
 * @author Jakub Konečný
 */
class RouterFactory {
  use \Nette\SmartObject;
  
  public function create(): RouteList {
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