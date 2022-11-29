<?php

namespace App\Controller;

use App\Repository\ContactsRepository;
use App\Repository\ProductsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'Accueil')]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'products' => $productsRepository->findAll(),
        ]);
    }
    #[Route('/admin', name: 'admin')]
    public function admin(ProductsRepository $productsRepository, CategoriesRepository $categoriesRepository, ContactsRepository $contactsRepository): Response
    {
        return $this->render('main/indexadmin.html.twig', [
            'products' => $productsRepository->findAll(),
            'categories' => $categoriesRepository->findAll(),
            'contacts' => $contactsRepository->findAll(),
        ]);
    }
}
