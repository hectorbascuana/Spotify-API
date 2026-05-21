<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cancion;
use App\Entity\Premium;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CancionController extends AbstractController
{
    public function canciones_guardadas(SerializerInterface $serializer, Request $request): Response
    {
        $userId = $request->get('userId');
        $user= $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $userId]);
        if (!$user) return new Response("User not found", Response::HTTP_NOT_FOUND);

        $canciones = $user->getCancion();
        $data = $serializer->serialize($canciones, 'json', ['groups' => ['cancion:read']]);
        return new Response($data, Response::HTTP_OK);
    }

    public function cancion_guardada(SerializerInterface $serializer, Request $request): Response
    {
        $cancionId = $request->get('cancionId');
        $cancion = $this->getDoctrine()->getRepository(Cancion::class)->findOneBy(['id' => $cancionId]);
        if (!$cancion) return new Response("Cancion not found", Response::HTTP_NOT_FOUND);
        $usuarioId = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $usuarioId]);
        if (!$usuario) return new Response("User not found", Response::HTTP_BAD_REQUEST);

        if ($request->getMethod() == 'DELETE') {
            if ($usuario->getCancion()->contains($cancion)) {
                $usuario->getCancion()->removeElement($cancion);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();


                return new Response('Deleted', 205);
            }
            return new Response('User do not follow this cancion', Response::HTTP_NOT_FOUND);

        }elseif ($request->getMethod() == 'PUT') {
            if ($usuario->getCancion()->contains($cancion)) {
                return new Response('User already follow this cancion', Response::HTTP_BAD_REQUEST);
            }
            $usuario->getCancion()->add($cancion);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new Response('Chaged', Response::HTTP_OK);
        }
        return new Response('Not allowed', Response::HTTP_FORBIDDEN);
    }

    public function canciones(SerializerInterface $serializer, Request $request): Response
    {
        $canciones = $this->getDoctrine()->getRepository(Cancion::class)->findAll();
        $data = $serializer->serialize($canciones, 'json', ['groups' => ['cancion:read']]);
        return new Response($data, Response::HTTP_OK);
    }

    public function cancion(Request $request, SerializerInterface $serializer): Response
    {
        $cancionId = $request->get('cancionId');
        $cancion = $this->getDoctrine()->getRepository(Cancion::class)->findOneBy(['id' => $cancionId]);
        if (!$cancion) return new Response("Cancion not found", Response::HTTP_NOT_FOUND);
        $data = $serializer->serialize($cancion, 'json', ['groups' => ['cancion:read']]);
        return new Response($data, Response::HTTP_OK);

    }
}
