<?php


namespace App\Order;


use App\Entity\Order;
use Twig\Environment;

class Mailing
{

    private $mailer, $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendNewOrderEmail(Order $order)
    {
        $message = (new \Swift_Message('Nouvelle Commande'))
            ->setFrom('dispatch@livretoo.com')
            ->setTo($order->getRestaurant()->getOwner()->getEmail())
            ->setBody(
                $this->twig->render(
                    'emails/new-order.html.twig', [
                        'name' => $order->getRestaurant()->getName(),
                        'orderId' => $order->getId(),
                        'customerName' => $order->getUser()->getFullname(),
                        'products' => $order->getProducts(),
                    ]
                ), 'text/html'
            )
        ;

        $this->mailer->send($message);
    }

}