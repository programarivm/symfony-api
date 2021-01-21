<?php

namespace App\Controller\Api;

use App\Entity\Product;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/product")
 */
class ProductController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/all")
     */
    public function all(SerializerInterface $serializer): Response
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);
        $products = $repo->findAll();

        return new Response(
            $serializer->serialize($products, 'json')
        );
    }

    /**
     * @Rest\Get("/featured/{currency}")
     */
    public function featured(SerializerInterface $serializer, string $currency = null): Response
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);
        $products = $repo->findBy(['isFeatured' => true]);

        return new Response(
            $serializer->serialize($products, 'json')
        );
    }
}
