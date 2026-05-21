<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Suscripcion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SuscripcionController extends AbstractController
{

    /**
     * @Route("/suscripcion")
     */


    public function usuario_suscripcion(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->get('id');
        $suscripcion = $this->getDoctrine()->getRepository(Suscripcion::class)->findOneBy(['id' => $id]);
        $data = $serializer->serialize($suscripcion, 'json', ['groups' => ['suscripcion:read', 'premium:read', 'usuario:read']]);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
