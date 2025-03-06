<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository): Response
    {
        // Récupération des catégories pour les afficher dans le formulaire de filtrage
        $categories = $categoryRepository->findAll();

        // Récupération des paramètres de la requête
        $searchTerm = $request->query->get('search', '');
        $categorySlug = $request->query->get('category', '');
        $minPrice = $request->query->get('minPrice', '');
        $maxPrice = $request->query->get('maxPrice', '');

        // Récupérer l'objet catégorie si une catégorie est sélectionnée
        $category = null;
        if ($categorySlug) {
            $category = $categoryRepository->findOneBy(['slug' => $categorySlug]);
        }

        // Rechercher les produits selon les critères
        $products = $productRepository->searchProducts($searchTerm, $category, $minPrice, $maxPrice);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'search' => $searchTerm,
            'category' => $category,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'categorySelected' => $category
        ]);
    }

    #[Route('/product/{slug}', name: 'app_product_show')]
    public function show(string $slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
