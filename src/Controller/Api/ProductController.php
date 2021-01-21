<?php

namespace App\Controller\Api;

use App\Entity\Product;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * @Rest\Get("/featured/{iso}")
     */
    public function featured(SerializerInterface $serializer, string $iso = null): Response
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);

        if ($iso) {
            if (!in_array($iso, Product::CURRENCY)) {
                throw new HttpException(400, "Currency is not valid.");
            }
            
            // TODO
            // Query the foreign exchange rates API
            $rate = 0.82;

            return new Response(
                $serializer->serialize($repo->featured($iso, $rate), 'json')
            );
        }

        return new Response(
            $serializer->serialize($repo->findBy(['isFeatured' => true]), 'json')
        );
    }
}
