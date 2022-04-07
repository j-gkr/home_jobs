<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191007191702 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hj_home_jobs ADD scheduled_home_job_id INT NOT NULL, DROP start_date');
        $this->addSql('ALTER TABLE hj_home_jobs ADD CONSTRAINT FK_C053074449C75EB4 FOREIGN KEY (scheduled_home_job_id) REFERENCES hj_scheduled_home_job (id)');
        $this->addSql('CREATE INDEX IDX_C053074449C75EB4 ON hj_home_jobs (scheduled_home_job_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hj_home_jobs DROP FOREIGN KEY FK_C053074449C75EB4');
        $this->addSql('DROP INDEX IDX_C053074449C75EB4 ON hj_home_jobs');
        $this->addSql('ALTER TABLE hj_home_jobs ADD start_date DATETIME NOT NULL, DROP scheduled_home_job_id');
    }
}
