<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Capitulo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CapituloController extends AbstractController
{


    public function Capitulo(Request $request, SerializerInterface $serializer): Response
    {
        $capituloId = $request->get("capituloId");
        $capitulos = $this->getDoctrine()->getRepository(Capitulo::class)->findOneBy(['id' => $capituloId]);
        $data = $serializer->serialize($capitulos, 'json', ['groups' => ['capitulo:read']]);
        return new Response($data, Response::HTTP_OK);
    }
}
