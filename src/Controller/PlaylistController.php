<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\AnyadeCancionPlaylist;
use App\Entity\Cancion;
use App\Entity\Eliminada;
use App\Entity\Playlist;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PlaylistController extends AbstractController
{


    public function playlist_usuario(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
        if (!$usuario) {
            return new Response('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }

        if ($request->getMethod() == 'POST') {
            $data = $request->getContent();
            $playlists = $serializer->deserialize($data, Playlist::class, 'json');
            $playlists->setFechaCreacion(new \DateTime('today'));
            $playlists->setUsuario($usuario);
            $playlists->setNumeroCanciones(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($playlists);
            $entityManager->flush();

            $data = $serializer->serialize($playlists, 'json', ['groups' => 'playlist:read']);
            return new Response($data, Response::HTTP_CREATED);
        } elseif ($request->getMethod() == 'GET') {
            $playlists = $this->getDoctrine()->getRepository(Playlist::class)->findBy(['usuario' => $usuario]);
            $data = $serializer->serialize($playlists, 'json', ['groups' => 'playlist:read']);
            return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }
        return new Response("Not allowed", Response::HTTP_FORBIDDEN);
    }



    public function playlists(SerializerInterface $serializer): Response
    {

        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $playlists = $qb
            ->select('p')
            ->from(Playlist::class, 'p')
            ->leftJoin(Eliminada::class, 'e', 'WITH', 'e.playlist = p')
            ->where('e.playlist IS NULL')
            ->getQuery()
            ->getResult();


        $data = $serializer->serialize($playlists, 'json', ['groups' => 'playlist:read']);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);




    }






    public function playlist(Request $request, SerializerInterface $serializer): Response
    {
        $playlistId = $request->get('playlistId');
        $playlist = $this->getDoctrine()->getRepository(Playlist::class)->findOneBy(['id' => $playlistId]);

        if (!$playlist) {
            return new Response(
                'Playlist no encontrada',
                Response::HTTP_NOT_FOUND
            );
        }


        $data = $serializer->serialize($playlist, 'json', ['groups' => 'playlist:read']);

        return new Response(
            $data,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    public function playlist_cancion(Request $request, SerializerInterface $serializer): Response
    {
        $playlistId = $request->get('playlistId');
        $playlist = $this->getDoctrine()->getRepository(Playlist::class)->findOneBy(['id' => $playlistId]);

        if (!$playlist) {
            return new Response(
                'Playlist no encontrada',
                Response::HTTP_NOT_FOUND
            );
        }

        if($request->getMethod() == 'GET') {
            $canciones = $this->getDoctrine()->getRepository(AnyadeCancionPlaylist::class)->findBy(['playlist' => $playlist]);
            if (!$canciones) return new Response("Sin canciones", Response::HTTP_NOT_FOUND);

            $data = $serializer->serialize($canciones, 'json', ['groups' => 'anyade:read']);
            return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);


        }elseif ($request->getMethod() == 'POST') {

            $data = json_decode($request->getContent(), true);
            $entityManager = $this->getDoctrine()->getManager();

            $usuario = $entityManager->getRepository(Usuario::class)->find($data['usuarioId']);


            $cancion = $entityManager->getRepository(Cancion::class)->find($data['cancionId']);

            if ($usuario === null || $cancion === null) {
                if ($usuario === null) return new Response("Usuario no encontrado", Response::HTTP_NOT_FOUND);
                else return new Response("Cancion no encontrada", Response::HTTP_NOT_FOUND);
            }
            $anyade = new AnyadeCancionPlaylist();
            $anyade->setCancion($cancion);
            $anyade->setUsuario($usuario);
            $anyade->setPlaylist($playlist);
            $anyade->setFechaAnyadida(new \DateTime('today'));

            $entityManager->persist($anyade);
            $entityManager->flush();

            $data = $serializer->serialize($anyade, 'json', ['groups' => 'anyade:read']);
            return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);

        }
        return new Response("Not allowed", Response::HTTP_FORBIDDEN);
    }

    public function playlist_delete_cancion(SerializerInterface $serializer, Request $request): Response
    {
        if ($request->getMethod() != 'DELETE') {
            return new Response("Not allowed", Response::HTTP_FORBIDDEN);
        }
        $playlistId = $request->get('playlistId');
        $playlist = $this->getDoctrine()->getRepository(Playlist::class)->findOneBy(['id' => $playlistId]);
        if (!$playlist) return new Response("Playlist no encontrada", Response::HTTP_NOT_FOUND);

        $cancionId = $request->get('cancionId');
        $cancion = $this->getDoctrine()->getRepository(Cancion::class)->findOneBy(['id' => $cancionId]);
        if (!$cancion) return new Response("Cancion no encontrada", Response::HTTP_NOT_FOUND);

        $anyade = $this->getDoctrine()->getRepository(AnyadeCancionPlaylist::class)->findOneBy([
            'playlist' => $playlist,
            'cancion' => $cancion
        ]);
        if (!$anyade) return new Response("Esta cancion no está en esta playlist", Response::HTTP_NOT_FOUND);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($anyade);
        $entityManager->flush();

        return new Response("Deleted", Response::HTTP_OK);
    }

    public function playlist_seguida(SerializerInterface $serializer, Request $request): Response
    {
        $playlistId = $request->get('playlistId');
        $playlist = $this->getDoctrine()->getRepository(Playlist::class)->findOneBy(['id' => $playlistId]);
        if (!$playlist) return new Response("playlist", Response::HTTP_NOT_FOUND);
        $usuarioId = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $usuarioId]);
        if (!$usuario) return new Response("User", Response::HTTP_NOT_FOUND);

        if ($request->getMethod() == 'DELETE') {
            if ($usuario->getPlaylist()->contains($playlist)) {
                $usuario->getPlaylist()->removeElement($playlist);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($usuario);
                $entityManager->flush();


                return new Response('Deleted', 205);
            }
            return new Response('Este usuario no sigue esta lista', Response::HTTP_FORBIDDEN);

        }elseif ($request->getMethod() == 'PUT') {
            if ($usuario->getPlaylist()->contains($playlist)) {
                return new Response('Este usuario ya sigue esta lista', Response::HTTP_OK);
            }
            $usuario->getPlaylist()->add($playlist);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new Response('Ha empezado a seguir a esta lista', Response::HTTP_OK);
        }
        return new Response('Not allowed', Response::HTTP_FORBIDDEN);
    }

    public function playlist_seguidas(SerializerInterface $serializer, Request $request): Response
    {
        if ($request->getMethod() != 'GET') {
            return new Response("Not allowed", Response::HTTP_FORBIDDEN);
        }

        $id = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
        if (!$usuario) return new Response("Usuario no encontrado", Response::HTTP_NOT_FOUND);

        $playlists = $usuario->getPlaylist();
        $data = $serializer->serialize($playlists, 'json', ['groups' => 'playlist:read']);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);

    }
}
