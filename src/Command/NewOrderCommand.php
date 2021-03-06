<?php

namespace App\Command;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Restaurant;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

class NewOrderCommand extends Command
{
    protected static $defaultName = 'app:new-order';
    private $manager, $registry;

    public function __construct(EntityManagerInterface $manager, Registry $registry)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate a new random order')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        # Get Restaurants and Users
        $restaurant = $this->getRandomRestaurant();
        $user = $this->getRandomUser();

        # Generate the new order
        $order = new Order();
        $order
            ->setDate(new \DateTime())
            ->setRestaurant($restaurant)
            ->setDeliveryInformations($this->getRandomInformations())
            ->setUser($user);

        # Adding random products to order
        for ($i = 0 ; $i < rand(2, 5) ; $i ++) {

            $order->addProduct(
                $this->getRandomProducts(
                    $restaurant->getId()
                )
            );
        }

        # Handle Workflow
        $workflow = $this->registry->get($order);

        try {

            # Send order to restaurant
            $workflow->apply($order, 'to_restaurant');

            # Persist Data
            $this->manager->persist($order);
            $this->manager->flush();

            # Send to Delivery Api
            $httpClient = HttpClient::create();

            $response = $httpClient->request('POST', 'http://api.biyn.media/api/deliveries', [
                'json' => [
                    'orderId' => $order->getId(),
                    'restaurantName' => $restaurant->getName(),
                    'restaurantPhone' => $restaurant->getPhone(),
                    'restaurantAddress' => $restaurant->getAddress() . ' ' . $restaurant->getZipCode() . ' ' . $restaurant->getCity(),
                    'customerFullname' => $user->getFullname(),
                    'customerPhone' => $user->getPhone(),
                    'customerAddress' => $user->getAddress(),
                    'deliveryInformations' => $order->getDeliveryInformations(),
                    'status' => $order->getStatus(),
                ],
            ]);

            $io->success('Congrats, You have a new order to handle !');

        } catch (LogicException $e) {

            # Transition non autorisé
            $io->warning($e->getMessage());
            $io->error('Ooops, ordering process failed.');
        }
    }

    /**
     * Get random restaurants from DB
     * @return Restaurant
     */
    private function getRandomRestaurant(): Restaurant
    {
        $restaurants = $this->manager->getRepository(Restaurant::class)
                ->findAll();

        return $restaurants[array_rand($restaurants)];
    }

    /**
     * Get random users from DB
     * @return User
     */
    private function getRandomUser(): User
    {
        $users = $this->manager->getRepository(User::class)
            ->findByStatus('ROLE_CUSTOMER');

        return $users[array_rand($users)];
    }

    /**
     * Get random products from DB
     * @param int $idRestaurant
     * @return Product
     */
    private function getRandomProducts(int $idRestaurant): Product
    {
        $products = $this->manager->getRepository(Product::class)
            ->findByRestaurant($idRestaurant);

        return $products[array_rand($products)];
    }

    private function getRandomInformations()
    {
        $informations = [
            'Le code pour entrer est la 1529',
            'Vous pouvez appeler pour que je vienne',
            'Je suis joignable au 06 98 76 54 34',
            'Je viendrais récupérer la commande à la porte'
        ];

        return $informations[array_rand($informations)];
    }
}
