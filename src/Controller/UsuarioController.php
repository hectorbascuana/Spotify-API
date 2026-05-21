<?php

namespace App\Controller;

use App\Entity\Calidad;
use App\Entity\Configuracion;
use App\Entity\Free;
use App\Entity\Idioma;
use App\Entity\Premium;
use App\Entity\TipoDescarga;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UsuarioController extends AbstractController
{
    public function usuarios( Request $request, SerializerInterface $serializer): Response
    {
        if ($request->isMethod('POST')) {
            //leemos query parameter (premium)
            $premium = $request->query->get('premium');

            // Leer usuario del body
            $data = $request->getContent();
            $usuario = $serializer->deserialize($data, Usuario::class, 'json', ['groups' => 'usuario:write']);

            if ($this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['username' => $usuario->getUsername()]) !== null) {
                return new Response('Username ya registrado', Response::HTTP_CONFLICT);
            }

            if ($this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['email' => $usuario->getEmail()]) !== null) {
                return new Response('Email ya registrado', Response::HTTP_CONFLICT);
            }
            $usuario->setFechaNacimiento(new \DateTime("now"));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);

            // dependiendo del tipo de usuario, creamos free o premium

            if ($premium) {
                $premium = new Premium();
                $premium->setUsuario($usuario);
                $fechaRenovacion = (new \DateTime('today'))->modify('+1 month');
                $premium->setFechaRenovacion($fechaRenovacion);
                $entityManager->persist($premium);
            }else{
                $free = new Free();
                $free->setUsuario($usuario);
                $fechaRevision = (new \DateTime('today'))->modify('+1 month');
                $free->setFechaRevision($fechaRevision);
                $entityManager->persist($free);
            }

            //Creamos configuración por defecto

            $configuracion = new Configuracion();
            $configuracion->setUsuario($usuario);
            $configuracion->setAutoplay(true);
            $configuracion->setAjuste(true);
            $configuracion->setNormalizacion(true);

            $calidad = $entityManager->getRepository(Calidad::class)->findOneBy(['id' => 1]);
            $idioma = $entityManager->getRepository(Idioma::class)->findOneBy(['id' => 1]);
            $tipoDescarga = $entityManager->getRepository(TipoDescarga::class)->findOneBy(['id' => 1]);

            $configuracion->setTipoDescarga($tipoDescarga);
            $configuracion->setIdioma($idioma);
            $configuracion->setCalidad($calidad);

            $entityManager->persist($configuracion);

            $entityManager->flush();

            $data = $serializer->serialize($usuario, 'json', ['groups' => 'usuario:read']);

            return new Response($data, 201, ['Content-Type' => 'application/json']);
        }
        if ($request->isMethod('GET')) {
            $usuarios = $this->getDoctrine()->getRepository(Usuario::class)->findAll();

            $data = $serializer->serialize($usuarios, 'json', ['groups' => 'usuario:read']);
            return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        return new Response("Not allowed", 405);
    }

    public function usuario(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->get('id');
        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['id' => $id]);
        if (!$usuario) {
            return new Response("Usuario no encontrado", Response::HTTP_NOT_FOUND);
        }

        if ($request->isMethod('GET')) {

            $data = $serializer->serialize($usuario, 'json', ['groups' => 'usuario:read']);
            return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }elseif ($request->isMethod('PUT')) {
            $data = $request->getContent();

            $putData = json_decode($data, true);

            $comprueba = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['username' => $putData['username']]);
            if ($comprueba == null || $comprueba->getId() !== $usuario->getId()) {
                return new Response('Username ya registrado', Response::HTTP_CONFLICT);
            }

            $comprueba = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['email' => $putData['email']]);
            if ($comprueba == null || $comprueba->getId() !== $usuario->getId()) {
                return new Response('Email ya registrado', Response::HTTP_CONFLICT);
            }
            
            if ($putData['genero'] != 'M'&& $putData['genero'] != 'F') {
                return new Response("Género no válido", Response::HTTP_BAD_REQUEST);
            }
            

            $serializer->deserialize($data, Usuario::class, 'json', ['groups' => 'usuario:write', 'object_to_populate' => $usuario] );

            $this->getDoctrine()->getManager()->flush();

            $data = $serializer->serialize($usuario, 'json', ['groups' => 'usuario:read']);
            return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);

        }elseif ($request->isMethod('DELETE')) {
            $entityManager = $this->getDoctrine()->getManager();

            $configuracion = $this->getDoctrine()->getRepository(Configuracion::class)->findOneBy(['usuario' => $usuario]);
            $entityManager->remove($configuracion);

            $premium = $this->getDoctrine()->getRepository(Premium::class)->findOneBy(['usuario' => $usuario]);
            if ($premium) {
                $entityManager->remove($premium);
            }

            $free = $this->getDoctrine()->getRepository(Free::class)->findOneBy(['usuario' => $usuario]);
            if ($free) {
                $entityManager->remove($free);
            }


            $entityManager->remove($usuario);
            $entityManager->flush();

            Return new Response('Deleted', 205, ['Content-Type' => 'application/json']);

        }

        return new Response("Not allowed", 405);

    }


    public function login(Request $request, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['email' => $data['email'], 'password' => $data['password']]);
        if (!$user) {
            return new Response("Username or password incorrect", Response::HTTP_NOT_FOUND);
        }
        $data = $serializer->serialize($user, 'json', ['groups' => 'usuario:read']);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}