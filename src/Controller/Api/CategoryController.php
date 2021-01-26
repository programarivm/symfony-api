<?php

namespace App\Controller\Api;

use App\Entity\Category;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryController extends AbstractFOSRestController
{
    public function all(SerializerInterface $serializer): Response
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $category = $repo->findAll();

        return new Response(
            $serializer->serialize($category, 'json')
        );
    }

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

    public function update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent());

        $repo = $this->getDoctrine()->getRepository(Category::class);
        $category = $repo->findOneBy(['id' => $id]);

        if (!$category) {
            throw new HttpException(400, "Category is not valid.");
        }

        // TODO: data validation

        $category->setName($data->name)
            ->setSlug($data->slug)
            ->setDescription($data->description);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return new Response(Response::HTTP_NO_CONTENT);
    }
}
