<?php

namespace App\Controller;

use App\Dictionaries\AvatarsDictionary;
use App\Dictionaries\CityDictionary;
use App\Dictionaries\SexDictionary;
use App\Repository\FriendsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /** @var int */
    private const LIMIT = 3;

    /** @var int */
    private const PAGE = 1;

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
    public function __construct(FriendsRepository $friendsRepository, EntityManagerInterface $em)
    {
        $this->friendsRepository = $friendsRepository;
        $this->em = $em;
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(
        PaginatorInterface $paginator,
        Request $request
    ) {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        $userId = $user->getId();

        return $this->render(
            'search/search.html.twig',
            [
                'options' => [],
                'pagination' => $paginator->paginate(
                    $this->em->createQuery(
                        "SELECT users FROM App\Entity\User users WHERE users.id != $userId"
                    ),
                    (int)$request->query->get('page', self::PAGE),
                    self::LIMIT
                ),
                'sentOffersIds' => $this->friendsRepository->getSentOffersIds($userId),
                'acceptedOffersIds' => $this->friendsRepository->getAcceptedOffersIds($userId),
                'dictionary' => [
                    'sex' => SexDictionary::get(),
                    'city' => CityDictionary::get(),
                    'avatars' => AvatarsDictionary::get()
                ]
            ]
        );
    }
}
