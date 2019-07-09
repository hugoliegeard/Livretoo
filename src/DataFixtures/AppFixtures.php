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
        foreach ($this->getUserData() as [$firstname, $lastname, $email, $password, $roles, $phone, $address, $zipCode, $city]) {
            $user = new User();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setPhone($phone);
            $user->setAddress($address);
            $user->setZipCode($zipCode);
            $user->setCity($city);
            $manager->persist($user);
            $this->addReference($lastname, $user);
        }
        $manager->flush();
    }

    private function loadRestaurants(ObjectManager $manager): void
    {
        foreach ($this->getRestaurantData() as [$title, $address, $zipCode, $city, $phone]) {
            $restaurant = new Restaurant();
            $restaurant->setName($title);
            $restaurant->setAddress($address);
            $restaurant->setZipCode($zipCode);
            $restaurant->setCity($city);
            $restaurant->setPhone($phone);
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
            ['Hugo', 'LIEGEARD', 'hugo@livretoo.fr', 'livretoo', ['ROLE_DISPATCHER'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Naël', 'FAWAL', 'nawel@livretoo.fr', 'livretoo', ['ROLE_CUSTOMER'], '0783 45 67 67', '88 Parc de la Paix', '95500', 'Gonesse'],
            ['Maxime', 'DELAYER', 'maxime@livretoo.fr', 'livretoo', ['ROLE_CUSTOMER'], '0783 45 67 67', '88 Parc de la Paix', '95500', 'Gonesse'],
            ['Bruno', 'COSTE', 'bruno.coste@livretoo.fr', 'livretoo', ['ROLE_DELIVERY'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Caroline', 'CHAPEAU', 'caroline.chapeau@livretoo.fr', 'livretoo', ['ROLE_DELIVERY'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Eric', 'VALETTE', 'eric.valette@livretoo.fr', 'livretoo', ['ROLE_DELIVERY'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Iulian', 'AMAREEI', 'iulian.amareei@livretoo.fr', 'livretoo', ['ROLE_DELIVERY'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Esther', 'VALENTIN', 'esther.valentin@livretoo.fr', 'livretoo', ['ROLE_MANAGER'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Charlène', 'BENKE', 'charlene.benke@livretoo.fr', 'livretoo', ['ROLE_MANAGER'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Alexia', 'JOACHIM', 'alexia.joachim@livretoo.fr', 'livretoo', ['ROLE_MANAGER'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Sebastien', 'VERGER', 'sebastien.verger@livretoo.fr', 'livretoo', ['ROLE_MANAGER'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Florence', 'GARABEDIAN', 'florence.garabedian@livretoo.fr', 'livretoo', ['ROLE_MANAGER'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
            ['Guillaume', 'DEROGUERRE', 'guillaume.deroguerre@livretoo.fr', 'livretoo', ['ROLE_MANAGER'], '0783 45 67 67', '20 Rue de Paris', '95500', 'Gonesse'],
        ];
    }

    private function getRestaurantData(): array
    {
        return [
            ['Les Terrasses de Lyon', '45 Rue de la Victoire', '95500', 'Gonesse', '01 55 38 95 42'],
            ['Bouchon Les Lyonnais', '45 Rue de la Victoire', '95500', 'Gonesse', '01 55 38 95 42'],
            ['Le Book-Lard', '45 Rue de la Victoire', '95500', 'Gonesse', '01 55 38 95 42'],
            ['Le Neuvième Art', '45 Rue de la Victoire', '95500', 'Gonesse', '01 55 38 95 42'],
            ['Cercle Rouge', '45 Rue de la Victoire', '95500', 'Gonesse', '01 55 38 95 42'],
            ['Le Cocon', '45 Rue de la Victoire', '95500', 'Gonesse', '01 55 38 95 42'],
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
        return $restaurants[array_rand($restaurants)][0];
    }

}
