<?php


namespace App\EventSubscriber;

use Gedmo\Loggable\LoggableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

/**
 * Class DoctrineExtensionSubscriber
 *
 * @package App\EventSubscriber
 */
class DoctrineExtensionSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggableListener
     */
    private $loggableListener;
    /**
     * @var Security
     */
    private $security;

    /**
     * DoctrineExtensionSubscriber constructor.
     *
     * @param LoggableListener $loggableListener
     * @param Security $security
     */
    public function __construct(LoggableListener $loggableListener, Security $security)
    {
        $this->loggableListener = $loggableListener;
        $this->security = $security;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        if ($this->security->getToken() !== null && $this->security->isGranted('ROLE_USER')) {
            $this->loggableListener->setUsername($this->security->getUser()->getUsername());
        }
    }
}