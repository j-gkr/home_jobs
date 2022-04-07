<?php


namespace App\DataFixtures;

use App\Entity\PaymentCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PaymentCategoryFixture
 *
 * @package App\DataFixtures
 */
class PaymentCategoryFixture extends Fixture implements FixtureGroupInterface
{
    // data array
    private $data = [
        ['name' => 'Lebensmittel', 'color' => '#1fcc4d'],
        ['name' => 'Reparatur', 'color' => '#dedb16'],
        ['name' => 'Haushalt', 'color' => '#0f83d6'],
        ['name' => 'Elektronik', 'color' => '#d40f0f'],
        ['name' => 'Freizeit', 'color' => '#cd11d4'],
        ['name' => 'Wohnen', 'color' => '#de7e10'],
        ['name' => 'Nebenkosten', 'color' => '#10c3de'],
        ['name' => 'Geschenke', 'color' => '#f00a6d'],
        ['name' => 'Sonstiges', 'color' => '#8a7f83'],
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $entry) {
            $category = new PaymentCategory();
            $category->setName($entry['name']);
            $category->setColor($entry['color']);
            $manager->persist($category);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['payment-category'];
    }
}