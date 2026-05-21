<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artista;
use App\Entity\Cancion;
use App\Entity\Eliminada;
use App\Entity\Playlist;
use App\Entity\Podcast;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BuscadorController extends AbstractController
{
    /**
     * @Route("/buscador", methods={"GET"})
     */
    public function buscador(Request $request, SerializerInterface $serializer): Response
    {
        $texto = $request->get('text');
        if ($texto === '') {
            return new Response('Missing query text', Response::HTTP_BAD_REQUEST);
        }

        $prefijo = strtolower($texto) . '%';
        $entityManager = $this->getDoctrine()->getManager();

        $canciones = $entityManager->createQueryBuilder()
            ->select('c')
            ->from(Cancion::class, 'c')
            ->where('LOWER(c.titulo) LIKE :prefijo')
            ->setParameter('prefijo', $prefijo)
            ->getQuery()
            ->getResult();

        $albums = $entityManager->createQueryBuilder()
            ->select('a')
            ->from(Album::class, 'a')
            ->where('LOWER(a.titulo) LIKE :prefijo')
            ->setParameter('prefijo', $prefijo)
            ->getQuery()
            ->getResult();

        $playlists = $entityManager->createQueryBuilder()
            ->select('p')
            ->from(Playlist::class, 'p')
            ->leftJoin(Eliminada::class, 'e', 'WITH', 'e.playlist = p')
            ->where('e.playlist IS NULL')
            ->andWhere('LOWER(p.titulo) LIKE :prefijo')
            ->setParameter('prefijo', $prefijo)
            ->getQuery()
            ->getResult();

        $artistas = $entityManager->createQueryBuilder()
            ->select('ar')
            ->from(Artista::class, 'ar')
            ->where('LOWER(ar.nombre) LIKE :prefijo')
            ->setParameter('prefijo', $prefijo)
            ->getQuery()
            ->getResult();

        $podcasts = $entityManager->createQueryBuilder()
            ->select('p')
            ->from(Podcast::class, 'p')
            ->where('LOWER(p.titulo) LIKE :prefijo')
            ->setParameter('prefijo', $prefijo)
            ->getQuery()
            ->getResult();

        $resultados = [
            'canciones' => $canciones,
            'albums' => $albums,
            'playlists' => $playlists,
            'artistas' => $artistas,
            'podcasts' => $podcasts,
        ];

        $data = $serializer->serialize($resultados, 'json', [
            'groups' => [
                'cancion:read',
                'album:read',
                'playlist:read',
                'artista:read',
                'podcast:read',
            ],
        ]);

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
