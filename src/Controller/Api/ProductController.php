<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Product;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductController extends AbstractFOSRestController
{
    public function all(SerializerInterface $serializer): Response
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);
        $products = $repo->findAll();

        return new Response(
            $serializer->serialize($products, 'json')
        );
    }

    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent());

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['id' => $data->category_id]);

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

    public function featured(
        HttpClientInterface $httpClient,
        SerializerInterface $serializer,
        string $iso = null
    ): Response
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);

        if ($iso) {
            if (!in_array($iso, Product::CURRENCY)) {
                throw new HttpException(400, "Currency is not valid.");
            }

            $symbols = $repo->symbols($iso);
            $response = $httpClient->request('GET', "https://api.exchangeratesapi.io/latest?base=$iso&symbols=$symbols");
            $latest = json_decode($response->getContent());
            $rate = $latest->rates->{$symbols};

            return new Response(
                $serializer->serialize($repo->featured($iso, $rate), 'json')
            );
        }

        return new Response(
            $serializer->serialize($repo->findBy(['isFeatured' => true]), 'json')
        );
    }
}
