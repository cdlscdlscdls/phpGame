<?php
class Player
{
  private $name;
  private $hp;
  private $mp;
  private $ap;
  private $dp;
  private $skills;
  private $commands;
  function __construct($name, $hp, $mp, $skills)
  {
    $this->name = $name;
    $this->hp = $hp;
    $this->mp = $mp;
    $this->skills = $skills;
    $this->commands = array();
  }

  public function setHp($hp)
  {
    $this->hp = $hp;
  }
  public function setMp($mp)
  {
    $this->mp = $mp;
  }
  public function setAp($ap)
  {
    $this->ap = $ap;
  }
  public function setDp($dp)
  {
    $this->dp = $dp;
  }
  public function setSkills($skills)
  {
    $this->skills = $skills;
  }
  public function setCommands()
  {
    $commands = array();
    $skills = $this->getSkills();
    foreach ($skills as $skill) {
      $skillMp = $skill->getMp();
      $myMp = $this->getMp();
      // print("skillMp: ${skillMp} \n");
      // print("myMp: ${myMp} \n");
      if (($myMp + $skillMp) >= 0) {
        $commands[] = $skill;
      }
    }
    $this->commands = $commands;
  }
  public function setStatus($selectCommand)
  {
    $mp = $this->getMp() + $selectCommand->getMp();
    $this->setMp($mp);
    $ap = $this->getAp() + $selectCommand->getAp();
    $this->setAp($ap);
    $dp = $this->getDp() + $selectCommand->getDp();
    $this->setDp($dp);
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
  public function getSkills()
  {
    return $this->skills;
  }
  public function getCommands()
  {
    return $this->commands;
  }
}