<?php

namespace Game\CharacterBundle\Manager;

use Doctrine\ORM\EntityManager;
use Game\BattleBundle\Model\BattleResult;
use Game\CharacterBundle\Entity\Character;
use Game\MonsterBundle\Entity\Monster;
use Game\CharacterBundle\Manager\CharacterManager;

class XPManager
{
    const LEVELUP_XP_NEEDED = 100;

    const LEVELUP_STAT_HEALTH = 1;
    const LEVELUP_STAT_MANA = 2;
    const LEVELUP_STAT_DAMAGE = 3;
    const LEVELUP_STAT_DEFENSE = 4;
    const LEVELUP_STAT_STR = 5;
    const LEVELUP_STAT_DEX = 6;
    const LEVELUP_STAT_INT = 7;
    const LEVELUP_STAT_SPI = 8;

    const XP_ALGORITHM_LEVEL_DIFFERENCE = 3;

    /** @var CharacterManager */
    protected $characterManager;

    /**
     * @param \Game\CharacterBundle\Manager\CharacterManager $characterManager
     */
    public function setCharacterManager($characterManager)
    {
        $this->characterManager = $characterManager;
    }

    public function flush()
    {
        $this->characterManager->flush();
    }

    /**
     * @param Character $char
     * @return bool
     */
    public function isReadyToLevelUp(Character $char)
    {
        return ($char->getXp() >= self::LEVELUP_XP_NEEDED);
    }

    /**
     * @param Character $char
     * @param $stat
     */
    public function levelUp(Character $char, $stat)
    {
        switch ($stat)
        {
            case self::LEVELUP_STAT_HEALTH:
                $char->setHp($char->getHp() + 10);
                break;
            case self::LEVELUP_STAT_MANA:
                $char->setMana($char->getMana() + 10);
                break;
            case self::LEVELUP_STAT_DAMAGE:
                $char->setDamage($char->getDamage() + 1);
                break;
            case self::LEVELUP_STAT_DEFENSE:
                $char->setDefense($char->getDefense() + 1);
                break;
            case self::LEVELUP_STAT_STR:
                $char->setStr($char->getStr() + 1);
                break;
            case self::LEVELUP_STAT_DEX:
                $char->setDex($char->getDex() + 1);
                break;
            case self::LEVELUP_STAT_INT:
                $char->setInt($char->getInt() + 1);
                break;
            case self::LEVELUP_STAT_SPI:
                $char->setSpi($char->getSpi() + 1);
                break;
        }
        $char->setXP($char->getXP() - self::LEVELUP_XP_NEEDED);
        $char->setLevel($char->getLevel()+1);
        $this->characterManager->persist($char);
    }

    /**
     * @param BattleResult $battleResult
     * @param Character $char
     * @return int
     */
    public function calculateXPFromBattleResult(BattleResult $battleResult, Character $char)
    {
        $monstersKilled = $battleResult->getMonstersKilled();
        $xp = 0;

        if (count($monstersKilled) > 0) {
            /** @var Monster $monster */
            foreach ($monstersKilled as $monster) {
                $charLvl = $char->getLevel();
                $monsterLvl = $monster->getLevel();
                $monsterXP = 0;
                if ($charLvl < $monsterLvl + self::XP_ALGORITHM_LEVEL_DIFFERENCE) {
                    $monsterXP = $monsterLvl - $charLvl;
                    if ($monsterXP <= 0) {
                        $monsterXP = 1;
                    }
                }
                $xp += $monsterXP;
            }
        }
        return $xp;
    }
}
