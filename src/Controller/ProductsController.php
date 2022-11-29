<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/products')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductsRepository $productsRepository, SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
      
        $product = new Products();
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $productsRepository->save($product, true);


            $brochureFile = $form->get('photoProduct')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                
                }

               
                $product->setPhotoProduct($newFilename);
            }
            $manager = $doctrine->getManager();
            $manager->persist($product);

            $manager->flush();


            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $product,
        ]);
    }
    #[Route('/details/{id}', name: 'app_products_showprod', methods: ['GET'])]
    public function showprod(Products $product): Response
    {
        return $this->render('products/showprod.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Products $product, ProductsRepository $productsRepository): Response
    {
        $form = $this->createForm(ProductsType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsRepository->save($product, true);

            return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('products/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_products_delete', methods: ['POST'])]
    public function delete(Request $request, Products $product, ProductsRepository $productsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productsRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_products_index', [], Response::HTTP_SEE_OTHER);
    }
}
