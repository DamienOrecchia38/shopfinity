<?php
namespace App\DataFixtures;

use App\Entity\Categories;
use App\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
class CategoriesFixture extends AbstractFixture
{
   public function load(ObjectManager $manager)
   {
      $categoriesName = ['Technologie', 'Complément alimentaire', 'Beauté', 'Potion magique'];

      for ($i = 0; $i < 4; $i ++) {
         $category = new Categories();
         $category->setTitle($categoriesName[$i]);
         $manager->persist($category);
         $this->setReference('category_' . $i, $category);
      }
   $manager->flush();
   }
}