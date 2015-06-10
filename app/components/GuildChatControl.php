<?php
namespace HeroesofAbenez\Chat;


/**
 * Guild Chat Control
 *
 * @author Jakub Konečný
 */
class GuildChatControl extends ChatControl {
  /**
   * @param \Nette\Database\Context $database
   * @param int $gid Guild's id
   */
  function __construct(\Nette\Database\Context $database, $gid) {
    parent::__construct($database, "chat_guild", "guild", $gid);
  }
}
?>