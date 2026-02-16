<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artista;
use App\Entity\Cancion;
use App\Entity\Playlist;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ArtistaController extends AbstractController
{

    public function artista_seguido(SerializerInterface $serializer, Request $request): Response
    {
        $artistaId = $request->get('artistaId');
        $artista = $this->getDoctrine()->getRepository(Artista::class)->findOneBy(['id' => $artistaId]);
        if (!$artista) return new Response("Artista not found", Response::HTTP_NOT_FOUND);
        $usuarioId = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $usuarioId]);
        if (!$usuario) return new Response("User not found", Response::HTTP_NOT_FOUND);

        if ($request->getMethod() == 'DELETE') {
            if ($usuario->getArtista()->contains($artista)) {
                $usuario->getArtista()->removeElement($artista);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();


                return new Response('Deleted', Response::HTTP_OK);
            }
            return new Response('User do not follow this playlist', Response::HTTP_FORBIDDEN);

        }elseif ($request->getMethod() == 'PUT') {
            if ($usuario->getArtista()->contains($artista)) {
                return new Response('User already follow this artist', Response::HTTP_OK);
            }
            $usuario->getArtista()->add($artista);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new Response('Chaged', Response::HTTP_OK);
        }
        return new Response('Not allowed', Response::HTTP_FORBIDDEN);
    }

    public function artistas_seguidos(SerializerInterface $serializer, Request $request): Response
    {
        if ($request->getMethod() != 'GET') {
            return new Response("Not allowed", Response::HTTP_FORBIDDEN);
        }

        $id = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
        if (!$usuario) return new Response("Usuario not found", Response::HTTP_NOT_FOUND);

        $artistas = $usuario->getArtista();
        $data = $serializer->serialize($artistas, 'json', ['groups' => 'artista:read']);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);

    }


    public function artistas(SerializerInterface $serializer, Request $request): Response
    {
        $artistas = $this->getDoctrine()->getRepository(Artista::class)->findAll();
        $data = $serializer->serialize($artistas, 'json', ['groups' => ['artista:read']]);
        return new Response($data, Response::HTTP_OK);
    }

    public function artista(Request $request, SerializerInterface $serializer): Response
    {
        $artistaId = $request->get('artistaId');
        $artista = $this->getDoctrine()->getRepository(Artista::class)->findOneBy(['id' => $artistaId]);
        if (!$artista) return new Response("Artista not found", Response::HTTP_NOT_FOUND);
        $data = $serializer->serialize($artista, 'json', ['groups' => ['artista:read']]);
        return new Response($data, Response::HTTP_OK);

    }

    public function artista_albums(SerializerInterface $serializer, Request $request): Response
    {
        $artistaId = $request->get('artistaId');
        $artista = $this->getDoctrine()->getRepository(Artista::class)->findOneBy(['id' => $artistaId]);
        if (!$artista) return new Response("Artista not found", Response::HTTP_NOT_FOUND);
        $albums = $this->getDoctrine()->getRepository(Album::class)->findBy(['artista' => $artista]);
        $data = $serializer->serialize($albums, 'json', ['groups' => ['album:read']]);
        return new Response($data, Response::HTTP_OK);
    }

    public function artista_caniones(SerializerInterface $serializer, Request $request): Response
    {
        $artistaId = $request->get('artistaId');
        $artista = $this->getDoctrine()->getRepository(Artista::class)->findOneBy(['id' => $artistaId]);
        if (!$artista) return new Response("Artista not found", Response::HTTP_NOT_FOUND);
        $albums = $this->getDoctrine()->getRepository(Album::class)->findBy(['artista' => $artista]);
        $canciones = [];
        foreach ($albums as $album) {
            if (!$album) return new Response("Album not found", Response::HTTP_NOT_FOUND);
            $canciones[] = $this->getDoctrine()->getRepository(Cancion::class)->findBy(['album' => $album]);
        }
        $data = $serializer->serialize($canciones, 'json', ['groups' => ['cancion:read']]);
        return new Response($data, Response::HTTP_OK);
    }

}
