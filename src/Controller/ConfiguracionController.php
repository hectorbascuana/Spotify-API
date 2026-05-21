<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Calidad;
use App\Entity\Configuracion;
use App\Entity\Idioma;
use App\Entity\TipoDescarga;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ConfiguracionController extends AbstractController
{

    public function configuracion_usuario(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->get('userId');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
        if (!$usuario) {
            return new Response('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }

        
        $configuracion = $this->getDoctrine()->getRepository(Configuracion::class)->findOneBy(['usuario' => $usuario]);
        if ($request->isMethod('PUT')) {
            $data = json_decode($request->getContent(), true);
            if ($data['calidad'] > 5 || $data['tipoDescarga'] > 5 || $data['idioma'] <= 0 || $data['calidad'] <= 0 || $data['tipoDescarga'] <=0 || $data['idioma'] >=6 ){
                return new Response("Datos invalidos", Response::HTTP_BAD_REQUEST);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $serializer->deserialize($request->getContent(), Configuracion::class, 'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $configuracion,
                'groups' => ['configuracion:read'],
                'allow_extra_attributes' => true
                ]);

            $calidad = $entityManager->getRepository(Calidad::class)->find($data['calidad']);
            if (!empty($calidad)) {
                $configuracion->setCalidad($calidad);
            }

            $idioma = $entityManager->getRepository(Idioma::class)->find($data['idioma']);
            if (!empty($idioma)) {
                $configuracion->setIdioma($idioma);
            }

            $tipoDescarga = $entityManager->getRepository(TipoDescarga::class)->find($data['tipoDescarga']);
            if (!empty($tipoDescarga)) {
                $configuracion->setTipoDescarga($tipoDescarga);
            }

            $this->getDoctrine()->getManager()->flush();

            $data = $serializer->serialize($configuracion, 'json', ['groups' => 'configuracion:read']);

            return new Response($data, Response::HTTP_ACCEPTED);


        }elseif ($request->isMethod('GET')) {
            $data = $serializer->serialize($configuracion, 'json', ['groups' => 'configuracion:read']);
            return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        return new Response("Not allowed", Response::HTTP_FORBIDDEN);



    }
}
