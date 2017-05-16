<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Data structure for request
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $from
 * @property-read string $to
 * @property-read string $type
 * @property-read int $sent
 * @property-read string $status
 */
class Request {
  use \Nette\SmartObject;
  
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
  
  /**
   * @return int
   */
  function getId(): int {
    return $this->id;
  }
  
  /**
   * @return string
   */
  function getName(): string {
    return $this->name;
  }
  
  /**
   * @return string
   */
  function getTo(): string {
    return $this->to;
  }
  
  /**
   * @return string
   */
  function getType(): string {
    return $this->type;
  }
  
  /**
   * @return int
   */
  function getSent() {
    return $this->sent;
  }
  
  /**
   * @return string
   */
  function getStatus(): string {
    return $this->status;
  }
  
  /**
   * @return string
   */
  function getFrom(): string {
    return $this->from;
  }
}
?>