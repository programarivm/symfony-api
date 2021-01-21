<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Product;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
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
     *
     * @return Response
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
     * @Rest\Post("/create")
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent());

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['id' => $data->category]);

        if (!$category) {
            throw new HttpException(400, "Product is not valid.");
        }

        // TODO: data validation

        $product = (new Product())
            ->setName($data->name)
            ->setPrice($data->price)
            ->setCurrency($data->currency)
            ->setIsFeatured($data->is_featured)
            ->setCategory($category);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response(Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/featured/{iso}")
     *
     * @return Response
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
