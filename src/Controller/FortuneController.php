<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\FortuneCookieRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FortuneController extends AbstractController
{
    /**
     * @Route("/fortune", name="app_fortune")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/FortuneController.php',
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homepage(CategoryRepository $categoryRepository, Request $request): Response
    {

        $buscar = $request->query->get('q');

        if($buscar){
            $categories = $categoryRepository->buscar($buscar);
        }else{
            $categories = $categoryRepository->findAllOrdered();
        }



        return $this->render('fortune/homepage.html.twig', compact('categories'));
    }

    /**
     * @Route("/category/{id}", name="category_show")
     * @throws NonUniqueResultException
     */
    public function showCategory(string $id, CategoryRepository $categoryRepository, FortuneCookieRepository $fortuneCookieRepository): Response
    {
        $category = $categoryRepository->findWithFortunesJoin($id);
        if (!$category) {
            throw $this->createNotFoundException();
        }

        $muestraFortuna = $fortuneCookieRepository->contarNroAMostrarPorCategory($category);



        return $this->render('fortune/showCategory.html.twig', compact('category', 'muestraFortuna'));
    }
}
