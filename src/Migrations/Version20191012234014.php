<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191012234014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adds the cron job commands to cron table;';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO cron_job(name, command, schedule, description, enabled) VALUES (\'HomeJobs Mail Notifications\', \'app:send-mail-notifications\', \'0 6 * * *\', \'Sends notification mails\', 1)');
        $this->addSql('INSERT INTO cron_job(name, command, schedule, description, enabled) VALUES (\'HomeJobs Generator\', \'app:generate-home-jobs\', \'0 6,20 * * *\', \'Generates concrete home jobs\', 1)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM cron_job WHERE name = \'HomeJobs Mail Notifications\'');
        $this->addSql('DELETE FROM cron_job WHERE name = \'HomeJobs Generator\'');
    }
}
