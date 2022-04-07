<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200105111221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hj_user ADD enabled_job_notification TINYINT(1) DEFAULT NULL, ADD enabled_payment_notification TINYINT(1) DEFAULT NULL');
        $this->addSql('UPDATE hj_user SET enabled_job_notification = 0 WHERE enabled_job_notification IS NULL');
        $this->addSql('UPDATE hj_user SET enabled_payment_notification = 0 WHERE enabled_payment_notification IS NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hj_user DROP enabled_job_notification, DROP enabled_payment_notification');
    }
}
