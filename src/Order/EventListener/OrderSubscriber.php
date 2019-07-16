<?php


namespace App\Order\EventListener;


use App\Entity\Order;
use App\Order\Mailing;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class OrderSubscriber implements EventSubscriberInterface
{

    private $logger, $mailing;

    public function __construct(LoggerInterface $logger, Mailing $mailing)
    {
        $this->logger = $logger;
        $this->mailing = $mailing;
    }

    public static function getSubscribedEvents()
    {
        return [
          'workflow.ordering_workflow.completed.to_restaurant' => 'onNewOrder'
        ];
    }

    /**
     * 1. Lorsqu'un commande arrive sur notre plateforme
     * @param Event $event
     */
    public function onNewOrder(Event $event)
    {
        /** @var Order $order */
        $order = $event->getSubject();

        # Enregistrement dans le log
        $this->logger->info('Nouvelle commande pour le restaurant : '
            . $order->getRestaurant()->getName());

        # Notification au dispatch (Système Interne)
        # Notification au client (Notification Push / SMS)
        # Notification au restaurant (Email)
        $this->mailing->sendNewOrderEmail($order);

    }
}
