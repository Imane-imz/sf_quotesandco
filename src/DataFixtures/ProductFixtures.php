<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Génère du contenu en français

        $categories = $manager->getRepository(Category::class)->findAll();
        if (!$categories) {
            throw new \Exception("Aucune catégorie trouvée. Exécutez d'abord CategoryFixtures.");
        }

        for ($i = 0; $i < 30; $i++) { // Générer 30 produits aléatoires
            $category = $faker->randomElement($categories);

            $product = new Product();
            $product->setName($faker->words(3, true));
            $product->setPrice($faker->randomFloat(2, 5, 200)); // Prix entre 5€ et 200€
            $product->setDescription($faker->sentence(12));
            $product->setCategory($category);
            $product->setSlug($this->slugger->slug($product->getName())->lower());

            $manager->persist($product);
            echo "^ Produit inséré: {$product->getName()}, Slug: {$product->getSlug()}\n";
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
