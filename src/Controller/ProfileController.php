<?php

namespace App\Controller;

use App\Dictionaries\AvatarsDictionary;
use App\Dictionaries\CityDictionary;
use App\Dictionaries\SexDictionary;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/profiles/{id}", name="show")
     */
    public function show(
        Request $request
    ) {
        $currentUser = $this->getUser();
        if ($currentUser === null) {
            return $this->redirectToRoute('app_login');
        }

        $profile = $this->userRepository->find(
            (int)$request->attributes->get('id')
        );

        return $this->render(
            'profile/profile.html.twig',
            [
                'profile' => $profile,
                'dictionary' => [
                    'sex' => SexDictionary::get(),
                    'city' => CityDictionary::get(),
                    'avatars' => AvatarsDictionary::get()
                ]
            ]
        );
    }
}
