<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191024212324 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create a wallet for each group';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // for each group create a wallet
        $this->addSql('INSERT INTO hj_wallet(group_id, name, created_at, updated_at)(SELECT id, \'Geldbeutel\', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP() FROM hj_group)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM hj_wallet WHERE group_id IS NOT NULL');
    }
}
