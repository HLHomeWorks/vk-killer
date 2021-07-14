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

class OffersController extends AbstractController
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
     * @Route("/offers", name="offers")
     */
    public function offers(
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

        $dql = "SELECT user_id 
            FROM friends 
            WHERE friend_id=$userId AND status=1
            LIMIT $limit OFFSET $offset";

        $ids = $em->getConnection()->fetchFirstColumn($dql);

        $implodedIds = implode(',',$ids);

        if (empty($implodedIds)) {
            return $this->render(
                'search/search.html.twig',
                [
                    'options' => [],
                    'pagination' => null,
                    'sentOffersIds' => [],
                    'acceptedOffersIds' => $ids,
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
            'offers/offers.html.twig',
            [
                'options' => [],
                'pagination' => $pagination,
                'sentOffersIds' => [],
                'acceptedOffersIds' => $ids,
                'dictionary' => [
                    'sex' => SexDictionary::get(),
                    'city' => CityDictionary::get(),
                    'avatars' => AvatarsDictionary::get()
                ]
            ]
        );
    }
}
