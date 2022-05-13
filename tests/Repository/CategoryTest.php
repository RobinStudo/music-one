<?php
namespace App\Tests\Repository;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends KernelTestCase
{
    public function testFindAll(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $categoryRepository = $container->get(CategoryRepository::class);

        $categories = $categoryRepository->findAll();

        $this->assertCount(
            $categoryRepository->count([]),
            $categories
        );
    }

    public function testCreation(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $categoryRepository = $container->get(CategoryRepository::class);

        $countBeforeCreation = $categoryRepository->count([]);

        $category = new Category();
        $category->setName('test');
        $category->setIcon('test');

        $em->persist($category);
        $em->flush();

        $this->assertGreaterThan(
            $countBeforeCreation,
            $categoryRepository->count([])
        );
    }

    public function testDeletation(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $categoryRepository = $container->get(CategoryRepository::class);

        $countBeforeDeletation = $categoryRepository->count([]);

        $category = $categoryRepository->findOneBy([
            'name' => 'test', 'icon' => 'test'
        ], ['id' => 'DESC']);

        $em->remove($category);
        $em->flush();

        $this->assertLessThan(
            $countBeforeDeletation,
            $categoryRepository->count([])
        );
    }
}
