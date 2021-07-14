<?php

namespace App\Entity;

use App\Repository\FriendsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FriendsRepository::class)
 * @ORM\Table(
 *     name="friends",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="friends", columns={"user_id", "friend_id"})
 *      }
 *  )
 */
class Friends
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="integer")
     */
    private $friendId;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getFriendId()
    {
        return $this->friendId;
    }

    /**
     * @param mixed $friendId
     */
    public function setFriendId($friendId): void
    {
        $this->friendId = $friendId;
    }
}
