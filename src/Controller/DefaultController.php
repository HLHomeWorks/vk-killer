<?php

namespace App\Controller;

use App\Dictionaries\AvatarsDictionary;
use App\Dictionaries\CityDictionary;
use App\Dictionaries\SexDictionary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'default/index.html.twig',
            [
                'dictionary' => [
                    'sex' => SexDictionary::get(),
                    'city' => CityDictionary::get(),
                    'avatars' => AvatarsDictionary::get()
                ]
            ]
        );
    }
}
