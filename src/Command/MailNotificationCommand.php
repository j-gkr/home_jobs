<?php


namespace App\Command;


use App\Manager\NotificationManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class MailNotificationCommand
 *
 * @package App\Command
 */
class MailNotificationCommand extends Command
{
    /**
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * MailNotificationCommand constructor.
     *
     * @param NotificationManager $notificationManager
     * @param string $name
     */
    public function __construct(NotificationManager $notificationManager, string $name = 'app:send-mail-notifications')
    {
        parent::__construct($name);
        $this->notificationManager = $notificationManager;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Sends mail notifications to the users!');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        if ($this->notificationManager->sendMailNotifications()) {
            $io->success('Mails has been sent successfully!');
        } else {
            $io->error('Mails has not been sent!');
        }
    }

}