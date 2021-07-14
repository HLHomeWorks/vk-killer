<?php

namespace App\Controller;

use App\Exceptions\NotAuthException;
use App\Repository\FriendsRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostFriendController extends AbstractController
{
    /**
     * @var FriendsRepository
     */
    private $friendsRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param FriendsRepository $friendsRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        FriendsRepository $friendsRepository,
        EntityManagerInterface $em
    ) {
        $this->friendsRepository = $friendsRepository;
        $this->em = $em;
    }

    /** @Route("/api/v1/friends/{id}", name="post_friends", methods={"POST"})
     * @return JsonResponse
     */
    public function add(Request $request): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new NotAuthException('Требуется авторизация', 401);
        }

        $userId = $user->getId();
        $friendId =  (int)$request->attributes->get('id');


        $dql = "SELECT id FROM friends 
                WHERE (user_id=$userId AND friend_id = $friendId) 
                OR (user_id=$friendId AND friend_id = $userId)";

        if ($this->em->getConnection()->fetchOne($dql)) {
            throw new RuntimeException(
                'Вы уже добавляли в друзья этого пользователя'
            );
        }
        $dql = "INSERT INTO friends (user_id, friend_id, status) 
                VALUES ($userId,$friendId,1)";
        $this->em->getConnection()->executeStatement($dql);

        return new JsonResponse(
            [
                'data' => 'OK',
                'status' => 200,
                'errors' => []
            ],
            200,
            []
        );
    }
}
