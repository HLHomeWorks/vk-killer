<?php

namespace App\Controller;

use App\Dictionaries\AvatarsDictionary;
use App\Dictionaries\CityDictionary;
use App\Dictionaries\SexDictionary;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FriendsController extends AbstractController
{
    /** @var int */
    private const LIMIT = 3;

    /** @var int */
    private const PAGE = 1;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Route("/friends", name="friends")
     */
    public function friends(
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        Request $request
    ) {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        $userId = $user->getId();

        $limit = self::LIMIT;
        $offset = self::LIMIT * ((int)$request->query->get('page', self::PAGE) - 1);

        $dql = "SELECT user_id, friend_id 
            FROM friends 
            WHERE (user_id=$userId OR friend_id=$userId) AND status=2
            LIMIT $limit
            OFFSET $offset";

        $ids = $em->getConnection()->fetchAllAssociative($dql);

        $preparedIds = $this->getPreparedFriendIds($userId, $ids);

        $implodedIds = implode(',',$preparedIds);

        if (empty($implodedIds)) {
            return $this->render(
                'search/search.html.twig',
                [
                    'options' => [],
                    'pagination' => null,
                    'sentOffersIds' => [],
                    'acceptedOffersIds' => $preparedIds,
                    'dictionary' => [
                        'sex' => SexDictionary::get(),
                        'city' => CityDictionary::get(),
                        'avatars' => AvatarsDictionary::get()
                    ]
                ]
            );
        }

        $dql   = "SELECT users FROM App\Entity\User users WHERE users.id IN ($implodedIds)";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query,
            (int)$request->query->get('page', self::PAGE),
            self::LIMIT
        );

        return $this->render(
            'search/search.html.twig',
            [
                'options' => [],
                'pagination' => $pagination,
                'sentOffersIds' => [],
                'acceptedOffersIds' => $preparedIds,
                'dictionary' => [
                    'sex' => SexDictionary::get(),
                    'city' => CityDictionary::get(),
                    'avatars' => AvatarsDictionary::get()
                ]
            ]
        );
    }

    /**
     * @param int $currentUserID
     * @param array $relationFriendIds
     * @return array
     */
    private function getPreparedFriendIds(int $currentUserID, array $relationFriendIds)
    {
        $ids = [];
        foreach ($relationFriendIds as $relationFriend) {
            if ($relationFriend['user_id'] == $currentUserID) {
                $ids[] = (int)$relationFriend['friend_id'];
            } else {
                $ids[] = (int)$relationFriend['user_id'];
            }
        }

        return $ids;
    }
}
