#!/usr/local/bin/ruby
class Action
  def initialize(id,ap,dp,mp,name)
    @id = id
    @ap  = ap
    @dp = dp
    @mp = mp
    @name = name
  end
  attr_accessor :id
  attr_accessor :ap
  attr_accessor :dp
  attr_accessor :mp
  attr_accessor :name
end
class Player
  def initialize(name,hp,mp,choices)
    @name = name
    @hp  = hp
    @mp  = mp
    @choices  = choices
    @ap  = 0
    @dp  = 0
  end
  attr_accessor :name
  attr_accessor :hp
  attr_accessor :mp
  attr_accessor :choices
  attr_accessor :ap
  attr_accessor :dp
  def reset()
    @ap = 0
    @dp = 0
  end
  def change_ap(ap)
    @ap = ap
  end
  def change_dp(dp)
    @dp = dp
  end
  def change_mp(mp)
    @mp = @mp + mp
  end
  def change_hp(hp)
    @hp = @hp + hp
  end
end
def choice_judge(player, choice)
  return if (player.mp + choice.mp ) < 0
  player.change_mp(choice.mp)
  player.change_ap(choice.ap)
  player.change_dp(choice.dp)
  player.change_hp(1) if choice.id == "tabe"
end
def damage_judge(p1, p2)
  p1_damage = p1.dp - p2.ap
  p2_damage = p2.dp - p1.ap
  return if p1_damage == p2_damage
  p1.change_hp(p1_damage) if p1_damage < 0 && p1_damage < p2_damage
  p2.change_hp(p2_damage) if p2_damage < 0 && p2_damage < p1_damage
end
def add_choice(player)
  player.choices.push(C) if player.mp > 0
  player.choices.push(D) if player.mp > 1
  player.choices.push(E) if player.mp > 1
  player.choices.push(F) if player.mp > 1
  cnt = player.mp >= 1 ? 3 : 2
  player.choices.slice!(cnt..player.choices.length) if player.mp < 2
  player.choices.uniq!
end
def battle
  # Availavle choice check
  add_choice(YOU)
  add_choice(ENEMY)
  # Announce
  puts "--- ROUND#{$cnt} ---"
  puts "  YOU = HP:#{YOU.hp} MP:#{YOU.mp}"
  puts "ENEMY = HP:#{ENEMY.hp} MP:#{ENEMY.mp}"
  # Choice chceck
  msg = ""
  YOU.choices.each_index { |idx|
    msg = msg + " #{idx} : #{YOU.choices[idx].name} |"
  }
  puts msg
  stdin = STDIN.gets
  if stdin.to_i < 0 || stdin.to_i >= YOU.choices.length then
    puts "Please input [0-#{YOU.choices.length-1}]"
    battle
  end
  your_choice = YOU.choices[stdin.to_i]
  enemy_choice = $cnt > 1 || ENEMY.mp > 0 ? ENEMY.choices.sample : ENEMY.choices[0]
  puts "YOU = #{your_choice.name} : ENEMY = #{enemy_choice.name}"
  # Judge
  choice_judge(YOU, your_choice)
  choice_judge(ENEMY, enemy_choice)
  damage_judge(YOU,ENEMY)
  # Result check
  if YOU.hp <= 0 then
    puts "--- YOU LOSE ---"
    puts "  YOU = HP:#{YOU.hp} MP:#{YOU.mp}"
    puts "ENEMY = HP:#{ENEMY.hp} MP:#{ENEMY.mp}"
    exit
  elsif ENEMY.hp <= 0 then
    puts "--- YOU WIN ---"
    puts "  YOU = HP:#{YOU.hp} MP:#{YOU.mp}"
    puts "ENEMY = HP:#{ENEMY.hp} MP:#{ENEMY.mp}"
    exit
  else
    YOU.reset
    ENEMY.reset
    $cnt = $cnt + 1
    battle
  end
end
A = Action.new("tame",0,1,1,"溜め")
B = Action.new("tate",0,2,0,"防御")
C = Action.new("tati",2,0,-1,"攻撃")
D = Action.new("tabe",0,1,-2,"回復")
E = Action.new("ootati",3,0,-2,"魔法")
F = Action.new("ootate",1,3,-2,"反撃")
YOU = Player.new("you",1,0,[A,B])
ENEMY = Player.new("enemy",3,0,[A,B])
$cnt = 1
battle
