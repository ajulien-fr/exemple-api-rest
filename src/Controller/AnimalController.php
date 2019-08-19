<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Animal;

class AnimalController extends FOSRestController
{
    /**
    * @Rest\Post("/api/animal")
    * @Rest\View()
    * @ParamConverter("animal", converter="fos_rest.request_body")
    */
    public function createAction(Animal $animal, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($animal);
        $em->flush();

        return $this->view($animal, Response::HTTP_CREATED);
    }

    /**
    * @Rest\Get("/api/animals")
    * @Rest\View()
    */
    public function listAction()
    {
        $animals = $this->getDoctrine()->getRepository(Animal::class)->findAll();

        return $this->view($animals, Response::HTTP_OK);
    }

    /**
    * @Rest\Delete("/api/animal/{id}")
    * @Rest\View()
    */
    public function removeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $animal = $em->getRepository(Animal::class)->find($request->get('id'));

        $em->remove($animal);
        $em->flush();

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
    * @Rest\Patch("/api/animal")
    * @Rest\View()
    * @ParamConverter("animal", converter="fos_rest.request_body")
    */
    public function updateAction(Animal $animal, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();

        $em->merge($animal);
        $em->flush();

        return $this->view($animal, Response::HTTP_OK);
    }

    /**
    * @Rest\Get("/api/animal/{id}")
    * @Rest\View()
    */
    public function getAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $animal = $em->getRepository(Animal::class)->find($request->get('id'));

        if (empty($animal)) return $this->view(null, Response::HTTP_NOT_FOUND);

        return $this->view($animal, Response::HTTP_OK);
    }
}
