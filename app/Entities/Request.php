<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Data structure for request
 *
 * @author Jakub Konečný
 */
class Request extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $from;
  /** @var string */
  protected $to;
  /** @var string */
  protected $type;
  /** @var int */
  protected $sent;
  /** @var string */
  protected $status;
  
  /**
   * @param int $id
   * @param string $from
   * @param string $to
   * @param string $type
   * @param int $sent
   * @param string $status
   */
  function __construct(int $id, string $from, string $to, string $type, $sent, string $status) {
    $this->id = $id;
    $this->from = $from;
    $this->to = $to;
    $this->type = $type;
    $this->sent = $sent;
    $this->status = $status;
  }
}
?>