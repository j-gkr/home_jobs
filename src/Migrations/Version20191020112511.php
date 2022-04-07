<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191020112511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hj_payment (id INT AUTO_INCREMENT NOT NULL, wallet_id INT NOT NULL, creator_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_94B59DD1712520F3 (wallet_id), INDEX IDX_94B59DD161220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hj_wallet (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, group_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_58AE21837E3C61F9 (owner_id), INDEX IDX_58AE2183FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hj_payment ADD CONSTRAINT FK_94B59DD1712520F3 FOREIGN KEY (wallet_id) REFERENCES hj_wallet (id)');
        $this->addSql('ALTER TABLE hj_payment ADD CONSTRAINT FK_94B59DD161220EA6 FOREIGN KEY (creator_id) REFERENCES hj_user (id)');
        $this->addSql('ALTER TABLE hj_wallet ADD CONSTRAINT FK_58AE21837E3C61F9 FOREIGN KEY (owner_id) REFERENCES hj_user (id)');
        $this->addSql('ALTER TABLE hj_wallet ADD CONSTRAINT FK_58AE2183FE54D947 FOREIGN KEY (group_id) REFERENCES hj_group (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hj_payment DROP FOREIGN KEY FK_94B59DD1712520F3');
        $this->addSql('DROP TABLE hj_payment');
        $this->addSql('DROP TABLE hj_wallet');
    }
}
