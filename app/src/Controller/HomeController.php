<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, MessageGenerator $messageGenerator): Response
    {
        $products = $productRepository->findAll();

        $productBestSeller = $productRepository->findByIsBestSeller(1);

        $productSpecialOffert = $productRepository->findByIsSpecialOffer(1);

        $productNewArrival = $productRepository->findByIsNewArrival(1);

        $productFeatured = $productRepository->findByIsFeatured(1);

        $message = $messageGenerator->getAdvertisingMessage();
        $this->addFlash('success', $message);
        
        // dd([$products, $productBestSeller, $productSpecialOffert, $productNewArrival, $productFeatured]);

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'productBestSeller' => $productBestSeller,
            'productSpecialOffert' => $productSpecialOffert,
            'productNewArrival' => $productNewArrival,
            'productFeatured' => $productFeatured,
        ]);
    }
}
