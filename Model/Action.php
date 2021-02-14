<?php
class Action
{
  private $id;
  private $ap;
  private $dp;
  private $mp;
  private $name;
  function __construct($id, $ap, $dp, $mp, $name)
  {
    $this->id = $id;
    $this->ap = $ap;
    $this->dp = $dp;
    $this->mp = $mp;
    $this->name = $name;
  }

  public function getHp()
  {
    return $this->hp;
  }
  public function getMp()
  {
    return $this->mp;
  }
  public function getAp()
  {
    return $this->ap;
  }
  public function getDp()
  {
    return $this->dp;
  }
  public function getName()
  {
    return $this->name;
  }
}