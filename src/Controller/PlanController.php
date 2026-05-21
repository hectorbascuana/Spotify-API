<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Free;
use App\Entity\Pago;
use App\Entity\Premium;
use App\Entity\Suscripcion;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class PlanController extends AbstractController
{


    public function usuario_plan(Request $request, SerializerInterface $serializer): Response
    {


        $id = $request->get('id');


        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);


        if (!$usuario) {
            return new Response("Usuario no encontrado", Response::HTTP_NOT_FOUND);

        }

        $premium = $this->getDoctrine()->getRepository(Premium::class)->findOneBy(['usuario' => $usuario]);

        if ($premium) {
            $data = [
                'premium' => [
                    'id' => $premium->getUsuario()->getId(),
                    'fecha_renovación' => $premium->getFechaRenovacion()
                ]
            ];
        } else {
            $free = $this->getDoctrine()->getRepository(Free::class)->findOneBy(['usuario' => $usuario]);

            if (!$free) {

                return new Response("Usuario sin plan", Response::HTTP_NOT_FOUND);
            }

            $data = [
                'free' => [
                    'id' => $free->getUsuario()->getId(),
                    'fecha_revision' => $free->getFechaRevision()
                ]
            ];
        }

        $response = $serializer->serialize($data, 'json');
        return new Response($response, Response::HTTP_OK);
    }



    public function usuario_premium(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->get('id');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
        $entityManager = $this->getDoctrine()->getManager();

        if (!$usuario) {
            return new Response("Usuario no encontrado", Response::HTTP_NOT_FOUND);
        }
        $premium = $this->getDoctrine()->getRepository(Premium::class)->findOneBy(['usuario' => $usuario]);
        if ($premium) {
            $premium->setFechaRenovacion($premium->getFechaRenovacion()->modify('+1 month'));
            $entityManager->persist($premium);
        }else{
            $free = $this->getDoctrine()->getRepository(Free::class)->findOneBy(['usuario' => $usuario]);
            $premium = new Premium();
            $premium->setUsuario($usuario);
            $premium->setFechaRenovacion((new \DateTime('today'))->modify('+1 month'));

            $entityManager->persist($premium);
            $entityManager->remove($free);
        }

        $entityManager->flush();
        $response = $serializer->serialize($premium, 'json', ['groups' => ['usuario:read', 'premium:read']]);
        return new Response($response, 201);
    }



}