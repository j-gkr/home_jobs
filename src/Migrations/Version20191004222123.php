<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191004222123 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hj_home_jobs (id INT AUTO_INCREMENT NOT NULL, group_id INT NOT NULL, editor_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, start_date DATETIME NOT NULL, deadline DATETIME NOT NULL, INDEX IDX_C0530744FE54D947 (group_id), INDEX IDX_C05307446995AC4C (editor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hj_home_jobs ADD CONSTRAINT FK_C0530744FE54D947 FOREIGN KEY (group_id) REFERENCES hj_group (id)');
        $this->addSql('ALTER TABLE hj_home_jobs ADD CONSTRAINT FK_C05307446995AC4C FOREIGN KEY (editor_id) REFERENCES hj_user (id)');
        $this->addSql('ALTER TABLE hj_scheduled_home_job ADD editor_id INT NOT NULL');
        $this->addSql('ALTER TABLE hj_scheduled_home_job ADD CONSTRAINT FK_1D48A2456995AC4C FOREIGN KEY (editor_id) REFERENCES hj_user (id)');
        $this->addSql('CREATE INDEX IDX_1D48A2456995AC4C ON hj_scheduled_home_job (editor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE hj_home_jobs');
        $this->addSql('ALTER TABLE hj_scheduled_home_job DROP FOREIGN KEY FK_1D48A2456995AC4C');
        $this->addSql('DROP INDEX IDX_1D48A2456995AC4C ON hj_scheduled_home_job');
        $this->addSql('ALTER TABLE hj_scheduled_home_job DROP editor_id');
    }
}
