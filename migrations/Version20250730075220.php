<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730075220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX IDX_64C19C15E237E06 ON category (name)');
        $this->addSql('CREATE INDEX IDX_64C19C1989D9B62 ON category (slug)');
        $this->addSql('CREATE INDEX IDX_5373C9668AC58B70 ON country (nationality)');
        $this->addSql('CREATE INDEX IDX_5373C966989D9B62 ON country (slug)');
        $this->addSql('CREATE INDEX IDX_232B318C989D9B62 ON game (slug)');
        $this->addSql('CREATE INDEX IDX_232B318C5E237E06 ON game (name)');
        $this->addSql('CREATE INDEX IDX_232B318CE0D4FDE1 ON game (published_at)');
        $this->addSql('CREATE INDEX IDX_9CE8D5468B8E8428 ON publisher (created_at)');
        $this->addSql('CREATE INDEX IDX_9CE8D5465E237E06 ON publisher (name)');
        $this->addSql('CREATE INDEX IDX_9CE8D546989D9B62 ON publisher (slug)');
        $this->addSql('CREATE INDEX IDX_794381C68B8E8428 ON review (created_at)');
        $this->addSql('CREATE INDEX IDX_8D93D6498B8E8428 ON user (created_at)');
        $this->addSql('CREATE INDEX IDX_8D93D6495E237E06 ON user (name)');
        $this->addSql('CREATE INDEX IDX_2F79BF7B8B8E8428 ON user_own_game (created_at)');
        $this->addSql('CREATE INDEX IDX_2F79BF7B8481F469 ON user_own_game (game_time)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_64C19C15E237E06 ON category');
        $this->addSql('DROP INDEX IDX_64C19C1989D9B62 ON category');
        $this->addSql('DROP INDEX IDX_5373C9668AC58B70 ON country');
        $this->addSql('DROP INDEX IDX_5373C966989D9B62 ON country');
        $this->addSql('DROP INDEX IDX_232B318C989D9B62 ON game');
        $this->addSql('DROP INDEX IDX_232B318C5E237E06 ON game');
        $this->addSql('DROP INDEX IDX_232B318CE0D4FDE1 ON game');
        $this->addSql('DROP INDEX IDX_9CE8D5468B8E8428 ON publisher');
        $this->addSql('DROP INDEX IDX_9CE8D5465E237E06 ON publisher');
        $this->addSql('DROP INDEX IDX_9CE8D546989D9B62 ON publisher');
        $this->addSql('DROP INDEX IDX_794381C68B8E8428 ON review');
        $this->addSql('DROP INDEX IDX_8D93D6498B8E8428 ON user');
        $this->addSql('DROP INDEX IDX_8D93D6495E237E06 ON user');
        $this->addSql('DROP INDEX IDX_2F79BF7B8B8E8428 ON user_own_game');
        $this->addSql('DROP INDEX IDX_2F79BF7B8481F469 ON user_own_game');
    }
}
