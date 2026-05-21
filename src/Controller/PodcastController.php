<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Capitulo;
use App\Entity\Podcast;
use App\Entity\Cancion;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PodcastController extends AbstractController
{


    public function podcast_seguido(SerializerInterface $serializer, Request $request): Response
    {
        $podcastId = $request->get('podcastId');
        $podcast = $this->getDoctrine()->getRepository(Podcast::class)->findOneBy(['id' => $podcastId]);
        if (!$podcast) return new Response("Podcast not found", Response::HTTP_NOT_FOUND);
        $usuarioId = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $usuarioId]);
        if (!$usuario) return new Response("User not found", Response::HTTP_NOT_FOUND);

        if ($request->getMethod() == 'DELETE') {
            if ($usuario->getPodcast()->contains($podcast)) {
                $usuario->getPodcast()->removeElement($podcast);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();


                return new Response('Deleted', 205);
            }
            return new Response('User do not follow this podcast', Response::HTTP_BAD_REQUEST);

        }elseif ($request->getMethod() == 'PUT') {
            if ($usuario->getPodcast()->contains($podcast)) {
                return new Response('User already follow this podcast', Response::HTTP_BAD_REQUEST);
            }
            $usuario->getPodcast()->add($podcast);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new Response('Chaged', Response::HTTP_OK);
        }
        return new Response('Not allowed', Response::HTTP_FORBIDDEN);
    }



    public function podcasts_seguidos(SerializerInterface $serializer, Request $request): Response
    {
        if ($request->getMethod() != 'GET') {
            return new Response("Not allowed", Response::HTTP_FORBIDDEN);
        }

        $id = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
        if (!$usuario) return new Response("User not found", Response::HTTP_NOT_FOUND);

        $podcasts = $usuario->getPodcast();
        $data = $serializer->serialize($podcasts, 'json', ['groups' => 'podcast:read']);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);

    }

    public function podcasts(SerializerInterface $serializer, Request $request): Response
    {
        $podcasts = $this->getDoctrine()->getRepository(Podcast::class)->findAll();
        $data = $serializer->serialize($podcasts, 'json', ['groups' => ['podcast:read']]);
        return new Response($data, Response::HTTP_OK);
    }

    public function podcast(Request $request, SerializerInterface $serializer): Response
    {
        $podcastId = $request->get('podcastId');
        $podcast = $this->getDoctrine()->getRepository(Podcast::class)->findOneBy(['id' => $podcastId]);
        if (!$podcast) return new Response("podcast not found", Response::HTTP_NOT_FOUND);
        $data = $serializer->serialize($podcast, 'json', ['groups' => ['podcast:read']]);
        return new Response($data, Response::HTTP_OK);

    }

    public function podcast_capitulos(SerializerInterface $serializer, Request $request): Response
    {
        $podcastId = $request->get('podcastId');
        $podcast = $this->getDoctrine()->getRepository(Podcast::class)->findOneBy(['id' => $podcastId]);
        if (!$podcast) return new Response("podcast not found", Response::HTTP_NOT_FOUND);
        $capitulos = $this->getDoctrine()->getRepository(Capitulo::class)->findBy(['podcast' => $podcast]);
        $data = $serializer->serialize($capitulos, 'json', ['groups' => ['capitulo:read']]);
        return new Response($data, Response::HTTP_OK);
    }

}
