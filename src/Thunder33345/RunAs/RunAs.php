<?php
declare(strict_types=true);
/** Created By Thunder33345 **/
namespace Thunder33345\RunAs;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class RunAs extends PluginBase implements Listener
{
  public function onLoad()
  {

  }

  public function onEnable()
  {

  }

  public function onDisable()
  {

  }

  public function onCommand(CommandSender $sender, Command $command, string $label, array $args):bool
  {
    if($command->getName() !== 'runas' OR $command->getName() !== 'runasexact') return true;
    if(!$sender->hasPermission('runas.use')) {
      $sender->sendMessage(TextFormat::RED."Insufficient Permission");
      return true;
    }
    if(count($args) < 2) {
      $sender->sendMessage(TextFormat::RED."/$label <player> <command> [opts]");
      return true;
    }
    $stringAs = $args[0];
    /** @var CommandSender|Null $runAs */
    $runAs = null;
    switch($command->getName()){
      case strtolower($stringAs) == 'console';
        $runAs = new ConsoleCommandSender();
        break;
      case "runas":
        $runAs = $this->getServer()->getPlayer($stringAs);
        break;
      case "runasexact":
        $runAs = $this->getServer()->getPlayerExact($stringAs);
        break;
    }
    if($runAs === null) {
      $sender->sendMessage(TextFormat::RED.'Player "'.$stringAs.'" not found!');
      return true;
    }
    $command = $args[1];
    $cmdLine = $command;
    if(count($args) > 2) {
      array_shift($args);
      array_shift($args);
      $cmdLine .= implode(' ',$args);
    }

    $runResult = $this->getServer()->getCommandMap()->dispatch($runAs,$cmdLine);

    if($runResult !== true) {
      $sender->sendMessage(TextFormat::RED."Fail to run \"$command\" as \"{$runAs->getName()}\", Command not found!");
    }
    return true;
  }
}