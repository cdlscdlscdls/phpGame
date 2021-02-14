<?php
require('./Model/Action.php');
require('./Model/Player.php');
require('./Controller/BattleController.php');

$charge = new Action("A",0,1,1,"溜め");
$block = new Action("B",0,2,0,"防御");
$attack = new Action("C",2,0,-1,"攻撃");
$cure = new Action("D",0,1,-2,"回復");
$magic = new Action("E",3,0,-2,"魔法");
$counter = new Action("F",1,3,-2,"反撃");

$you = new Player("you",1,0,array($charge, $block, $attack, $magic));
$enemy = new Player("enemy",3,0,array($charge, $block, $attack, $counter));
$cnt = 1;

function commandJudge($player1, $player2)
{
  $player1sDamage = $player1->getDp() - $player2->getAp();
  $player2sDamage = $player2->getDp() - $player1->getAp();

  // ダメージ量が同じ場合は判定不要
  if ($player1sDamage == $player2sDamage){
    return;
  }
  // ダメージ計算
  if ($player1sDamage < 0 && $player1sDamage < $player2sDamage) {
    $player1Hp = $player1->getHp() + $player1sDamage;
    $player1->setHp($player1Hp);
  }
  if ($player2sDamage < 0 && $player2sDamage < $player1sDamage) {
    $player2Hp = $player2->getHp() + $player2sDamage;
    $player2->setHp($player2Hp);
  }
}
function resetStatus($player) {
  // AP/DP初期化
  $player->setAp(0);
  $player->setDp(0);
}

function main($you, $enemy, $cnt) {
  # Commands set
  $you->setCommands();
  $enemy->setCommands();

  # Battle start
  $yourHp = $you->getHp();
  $yourMp = $you->getMp();
  $enemysHp = $enemy->getHp();
  $enemysMp = $enemy->getMp();
  print("--- ROUND ${cnt} --- \n");
  print("  YOU = HP:${yourHp} MP:${yourMp} \n");
  print("ENEMY = HP:${enemysHp} MP:${enemysMp} \n");

  # Command disp
  $msg = '';
  $yourCommands = $you->getCommands();
  $enemysSelectCommands = $enemy->getCommands();
  foreach ($yourCommands as $idx => $yourCommand) {
    $yourCommandName = $yourCommand->getName();
    $msg = $msg ." ${idx} : ${yourCommandName} |";
  }
  print($msg ." \n");

  # Command select
  $stdin = (int) fgets(STDIN);
  $maxNum = count($you->getCommands()) - 1;
  if ( $stdin < 0 || $stdin > $maxNum) {
    print("Please input [0-${maxNum}] \n");
    main($you, $enemy, $cnt);
  }
  $yourSelectCommand = $yourCommands[$stdin];
  $enemysSelectCommand = $cnt > 1 || $enemy->getMp() > 0 ? $enemysSelectCommands[array_rand($enemysSelectCommands)] : $enemysSelectCommands[0];
  $yourSelectCommandName = $yourSelectCommand->getName();
  $enemysSelectCommandName = $enemysSelectCommand->getName();
  print("YOU = ${yourSelectCommandName} : ENEMY = ${enemysSelectCommandName} \n");

  # Judge
  $you->setStatus($yourSelectCommand);
  $enemy->setStatus($enemysSelectCommand);
  commandJudge($you, $enemy);
  resetStatus($you);
  resetStatus($enemy);
  # Result check
  $yourHp = $you->getHp();
  $yourMp = $you->getMp();
  $enemysHp = $enemy->getHp();
  $enemysMp = $enemy->getMp();
  if ($you->getHp() <= 0) {
    print("--- YOU LOSE --- \n");
    print("  YOU = HP:${yourHp} MP:${yourMp} \n");
    print("ENEMY = HP:${enemysHp} MP:${enemysMp} \n");
    exit;
  } else if ($enemy->getHp() <= 0) {
    print("--- YOU WIN --- \n");
    print("  YOU = HP:${yourHp} MP:${yourMp} \n");
    print("ENEMY = HP:${enemysHp} MP:${enemysMp} \n");
    exit;
  } else {
    $cnt = $cnt + 1;
    main($you, $enemy, $cnt);
  }
}

main($you, $enemy, $cnt);
