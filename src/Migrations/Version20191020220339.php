<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191020220339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hj_payment_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hj_payment ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE hj_payment ADD CONSTRAINT FK_94B59DD112469DE2 FOREIGN KEY (category_id) REFERENCES hj_payment_category (id)');
        $this->addSql('CREATE INDEX IDX_94B59DD112469DE2 ON hj_payment (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hj_payment DROP FOREIGN KEY FK_94B59DD112469DE2');
        $this->addSql('DROP TABLE hj_payment_category');
        $this->addSql('DROP INDEX IDX_94B59DD112469DE2 ON hj_payment');
        $this->addSql('ALTER TABLE hj_payment DROP category_id');
    }
}
