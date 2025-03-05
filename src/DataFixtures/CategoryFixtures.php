<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = ['Vêtements', 'Électronique', 'Livres', 'Accessoires'];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $slug = $this->slugger->slug($categoryName)->lower(); // Ajout du slug
            $category->setSlug($slug);

            dump("Category inserted: {$categoryName}, Slug: {$slug}"); // DEBUG
            
            $manager->persist($category);
        }

        $manager->flush();
    }
}
