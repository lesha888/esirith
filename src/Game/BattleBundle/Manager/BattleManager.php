<?php

namespace Game\BattleBundle\Manager;

use Game\BattleBundle\Entity\BattleMonster;
use Game\CharacterBundle\Entity\Character;
use Game\CoreBundle\Manager\CoreManager;
use Game\MapBundle\Entity\Poi;
use Game\MonsterBundle\Entity\Monster;
use Game\MonsterBundle\Model\MonsterGroup;
use Game\BattleBundle\Entity\Battle;

class BattleManager extends CoreManager
{

    /**
     * @return BattleRepository
     */
    protected function getRepository()
    {
        return parent::getRepository();
    }

    /**
     * Crea una batalla PvM
     *
     * @param Character $char
     * @param MonsterGroup $monsters
     * @param Poi $poi
     * @return Battle
     */
    public function createMonsterBattle(Character $char, MonsterGroup $monsters, Poi $poi)
    {
        $battle = new Battle();
        $battle->setCharacter($char);
        $battle->setStatus(Battle::STATUS_PENDING);

        foreach ($monsters as $monsterItem)
        {
            $battleMonster = new BattleMonster();
            $battleMonster->setMonster($monsterItem->getMonster());
            $battleMonster->setNumber($monsterItem->getNumber());
            $battleMonster->setBattle($battle);
            $this->persist($battleMonster);
        }
        $this->persist($battle);

        return $battle;
    }


}
