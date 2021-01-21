<?php

namespace App\Controller\Api;

use App\Entity\Category;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/all")
     */
    public function all(SerializerInterface $serializer): Response
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $category = $repo->findAll();

        return new Response(
            $serializer->serialize($category, 'json')
        );
    }
}
