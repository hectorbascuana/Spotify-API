<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Cancion;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AlbumController extends AbstractController
{

    /**
     * @Route("/album")
     */
    public function album_seguido(SerializerInterface $serializer, Request $request): Response
    {
        $album = $request->get('albumId');
        $album = $this->getDoctrine()->getRepository(Album::class)->findOneBy(['id' => $album]);
        if (!$album) return new Response("Album not found", Response::HTTP_NOT_FOUND);
        $usuarioId = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $usuarioId]);
        if (!$usuario) return new Response("User not found", Response::HTTP_NOT_FOUND);

        if ($request->getMethod() == 'DELETE') {
            if ($usuario->getAlbum()->contains($album)) {
                $usuario->getAlbum()->removeElement($album);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();


                return new Response('Deleted', Response::HTTP_OK);
            }
            return new Response('User do not follow this playlist', Response::HTTP_FORBIDDEN);

        }elseif ($request->getMethod() == 'PUT') {
            if ($usuario->getAlbum()->contains($album)) {
                return new Response('User already follow this album', Response::HTTP_OK);
            }
            $usuario->getAlbum()->add($album);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new Response('Chaged', Response::HTTP_OK);
        }
        return new Response('Not allowed', Response::HTTP_FORBIDDEN);
    }


    public function albums_seguidos(SerializerInterface $serializer, Request $request): Response
    {
        if ($request->getMethod() != 'GET') {
            return new Response("Not allowed", Response::HTTP_FORBIDDEN);
        }

        $id = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
        if (!$usuario) return new Response("Usuario not found", Response::HTTP_NOT_FOUND);

        $album = $usuario->getAlbum();
        $data = $serializer->serialize($album, 'json', ['groups' => 'album:read']);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);

    }

    public function albums(SerializerInterface $serializer, Request $request): Response
    {
        $albums = $this->getDoctrine()->getRepository(Album::class)->findAll();
        $data = $serializer->serialize($albums, 'json', ['groups' => ['album:read']]);
        return new Response($data, Response::HTTP_OK);
    }

    public function album(Request $request, SerializerInterface $serializer): Response
    {
        $albumId = $request->get('albumId');
        $album = $this->getDoctrine()->getRepository(Album::class)->findOneBy(['id' => $albumId]);
        if (!$album) return new Response("album not found", Response::HTTP_NOT_FOUND);
        $data = $serializer->serialize($album, 'json', ['groups' => ['album:read']]);
        return new Response($data, Response::HTTP_OK);

    }

    public function album_canciones(SerializerInterface $serializer, Request $request): Response
    {
        $albumId = $request->get('albumId');
        $album = $this->getDoctrine()->getRepository(Album::class)->findOneBy(['id' => $albumId]);
        if (!$album) return new Response("album not found", Response::HTTP_NOT_FOUND);
        $canciones = $this->getDoctrine()->getRepository(Cancion::class)->findBy(['album' => $album]);
        $data = $serializer->serialize($canciones, 'json', ['groups' => ['cancion:read']]);
        return new Response($data, Response::HTTP_OK);
    }


}
