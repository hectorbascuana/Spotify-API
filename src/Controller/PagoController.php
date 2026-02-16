<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Pago;
use App\Entity\Premium;
use App\Entity\Suscripcion;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PagoController extends AbstractController
{

    /**
     * @Route("/pago")
     */
    public function usuario_pagos(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->get('id');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
        $premium = $this->getDoctrine()->getRepository(Premium::class)->findOneBy(['usuario' => $usuario]);
        if (!$premium) {
            return new Response("El usuario no tiene pagos", Response::HTTP_NOT_FOUND);
        }
        $suscripciones = $this->getDoctrine()->getRepository(Suscripcion::class)->findBy(['premiumUsuario'=> $premium]);
        $pagos=[];

        foreach ($suscripciones as $suscripcion) {
            $pagos[]=$this->getDoctrine()->getRepository(Pago::class)->findBy(['suscripcion'=> $suscripcion]);
        }
        $data = $serializer->serialize($pagos, 'json', ['groups' => ['pago:read']]);


        return new Response($data, Response::HTTP_OK);
    }
}
