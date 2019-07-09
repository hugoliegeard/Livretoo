<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Restaurant;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;
    private $owners = [
        'VALENTIN',
        'BENKE',
        'JOACHIM',
        'VERGER',
        'GARABEDIAN',
        'DEROGUERRE',
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        # Chargement des utilisateurs
        $this->loadUsers($manager);

        # Chargement des restaurants
        $this->loadRestaurants($manager);

        # Chargement des produits
        $this->loadProducts($manager);

    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$firstname, $lastname, $email, $password, $roles]) {
            $user = new User();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $manager->persist($user);
            $this->addReference($lastname, $user);
        }
        $manager->flush();
    }

    private function loadRestaurants(ObjectManager $manager): void
    {
        foreach ($this->getRestaurantData() as $title) {
            $restaurant = new Restaurant();
            $restaurant->setName($title);
            $restaurant->setOwner($this->getReference($this->getRandomUniqueOwner()));
            $manager->persist($restaurant);
            $this->addReference($title, $restaurant);
        }
        $manager->flush();
    }

    private function loadProducts(ObjectManager $manager): void
    {
        foreach ($this->getProductData() as $productName) {

            $product = new Product();
            $product->setName($productName);

            for($i = 0 ; $i < 5 ; $i++) {
                $product->setRestaurant(
                    $this->getReference(
                        $this->getRestaurant()
                    )
                );
                $manager->persist($product);
                $manager->flush();
            }
        }
    }

    private function getUserData(): array
    {
        return [
            ['Hugo', 'LIEGEARD', 'hugo@livretoo.fr', 'livretoo', ['ROLE_DISPATCHER']],
            ['Naël', 'FAWAL', 'nawel@livretoo.fr', 'livretoo', ['ROLE_CUSTOMER']],
            ['Maxime', 'DELAYER', 'maxime@livretoo.fr', 'livretoo', ['ROLE_CUSTOMER']],
            ['Bruno', 'COSTE', 'bruno.coste@livretoo.fr', 'livretoo', ['ROLE_DELIVERY']],
            ['Caroline', 'CHAPEAU', 'caroline.chapeau@livretoo.fr', 'livretoo', ['ROLE_DELIVERY']],
            ['Eric', 'VALETTE', 'eric.valette@livretoo.fr', 'livretoo', ['ROLE_DELIVERY']],
            ['Iulian', 'AMAREEI', 'iulian.amareei@livretoo.fr', 'livretoo', ['ROLE_DELIVERY']],
            ['Esther', 'VALENTIN', 'esther.valentin@livretoo.fr', 'livretoo', ['ROLE_MANAGER']],
            ['Charlène', 'BENKE', 'charlene.benke@livretoo.fr', 'livretoo', ['ROLE_MANAGER']],
            ['Alexia', 'JOACHIM', 'alexia.joachim@livretoo.fr', 'livretoo', ['ROLE_MANAGER']],
            ['Sebastien', 'VERGER', 'sebastien.verger@livretoo.fr', 'livretoo', ['ROLE_MANAGER']],
            ['Florence', 'GARABEDIAN', 'florence.garabedian@livretoo.fr', 'livretoo', ['ROLE_MANAGER']],
            ['Guillaume', 'DEROGUERRE', 'guillaume.deroguerre@livretoo.fr', 'livretoo', ['ROLE_MANAGER']],
        ];
    }

    private function getRestaurantData(): array
    {
        return [
            'Les Terrasses de Lyon',
            'Bouchon Les Lyonnais',
            'Le Book-Lard',
            'Le Neuvième Art',
            'Cercle Rouge',
            'Le Cocon',
        ];
    }

    private function getProductData(): array
    {
        return [
            'Ratatouille méridionale',
            'Quiche gourmande aux poivrons',
            'Lasagnes au saumon et aux épinards',
            'Paella de marisco',
            'Osso bucco milanaise',
            'Choucroute alsacienne',
            'Risotto aux champignons',
            'Couscous tunisien traditionnel',
            'Lasagnes à la bolognaise',
            'Tacos mexicains',
            'Velouté de Potiron et Carottes',
            'Flan de courgettes',
            'Salade de riz d\'été facile',
            'Bruschetta (Italie)',
            'Soupe à l\'oignon',
            'Saumon en papillote',
            'Pissaladière',
        ];
    }

    private function getRandomUniqueOwner()
    {
        shuffle($this->owners);
        return array_pop($this->owners);
    }

    private function getRestaurant()
    {
        $restaurants = $this->getRestaurantData();
        return $restaurants[array_rand($restaurants)];
    }

}
