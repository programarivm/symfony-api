<?php

namespace App\Controller\Api;

use App\Entity\Category;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
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
     *
     * @return Response
     */
    public function all(SerializerInterface $serializer): Response
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $category = $repo->findAll();

        return new Response(
            $serializer->serialize($category, 'json')
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

        $category = (new Category())
            ->setName($data->name)
            ->setSlug($data->slug)
            ->setDescription($data->description);

        // TODO: data validation

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return new Response(Response::HTTP_CREATED);
    }

    /**
     * @Rest\Delete("/delete/{id}")
     *
     * @return Response
     */
    public function delete(int $id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $category = $repo->findOneById($id);

        if (!$category) {
            throw new HttpException(404, "Category does not exist.");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return new Response(Response::HTTP_NO_CONTENT);
    }
}
