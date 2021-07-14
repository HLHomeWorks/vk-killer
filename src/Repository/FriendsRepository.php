<?php

namespace App\Repository;

use App\Entity\Friends;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Friends|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friends|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friends[]    findAll()
 * @method Friends[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friends::class);
    }

    /**
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getSentOffersIds(int $userId): array
    {
        return array_merge(
            $this->_em->getConnection()->fetchFirstColumn(
                "SELECT friend_id FROM friends WHERE user_id=$userId AND status=1"
            ),
            $this->_em->getConnection()->fetchFirstColumn(
                "SELECT user_id FROM friends WHERE friend_id=$userId AND status=1"
            )
        );
    }

    /**
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getAcceptedOffersIds(int $userId): array
    {
        return array_merge(
            $this->_em->getConnection()->fetchFirstColumn(
                "SELECT friend_id FROM friends WHERE user_id=$userId AND status=2"
            ),
            $this->_em->getConnection()->fetchFirstColumn(
                "SELECT user_id FROM friends WHERE friend_id=$userId AND status=2"
            )
        );
    }
}
