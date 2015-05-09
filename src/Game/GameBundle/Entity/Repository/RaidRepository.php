<?php

namespace Game\GameBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Game\CharacterBundle\Entity\Character;
use Game\GameBundle\Entity\Boss;
use Game\GameBundle\Entity\Raid;

/**
 * RaidRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RaidRepository extends EntityRepository
{
    /**
     * @param Boss $boss
     * @return array
     */
    public function getActiveRaids(Boss $boss)
    {
        $qb = $this->createQueryBuilder('raid');

        $qb
            ->select('raid, char')
            ->join('raid.character', 'char')
            ->where('raid.boss = :boss')
            ->andWhere('raid.status = :status')
            ->setParameters(array('boss' => $boss, 'status' => Raid::STATUS_WAITING));

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Character $char
     * @param Boss $boss
     * @return mixed
     */
    public function getCharacterRaidAgainstBoss(Character $char, Boss $boss)
    {
        $qb = $this->createQueryBuilder('raid');

        $qb
            ->select('raid')
            ->where('raid.boss = :boss')
            ->andWhere('raid.status = :status')
            ->andWhere('raid.character = :char')
            ->setParameters(array('boss' => $boss, 'status' => Raid::STATUS_WAITING, 'char' => $char));

        return $qb->getQuery()->getOneOrNullResult();
    }
}