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

class DeleteFriendController extends AbstractController
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

    /** @Route("/api/v1/friends/{id}", name="delete_friends", methods={"DELETE"})
     * @return JsonResponse
     */
    public function delete(Request $request): Response
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

        if (!$this->em->getConnection()->fetchOne($dql)) {
            throw new RuntimeException(
                'Пользователя нет у вас в друзьях'
            );
        }
        $dql = "DELETE FROM friends 
                WHERE (user_id = $userId AND friend_id=$friendId)
                OR (user_id = $friendId AND friend_id=$userId)";
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
