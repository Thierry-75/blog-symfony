<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017144848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD register_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4976CB7E FOREIGN KEY (register_id) REFERENCES register (id)');
        $this->addSql('CREATE INDEX IDX_9474526C4976CB7E ON comment (register_id)');
        $this->addSql('ALTER TABLE register ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE register ADD CONSTRAINT FK_5FF94014A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5FF94014A76ED395 ON register (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4976CB7E');
        $this->addSql('DROP INDEX IDX_9474526C4976CB7E ON comment');
        $this->addSql('ALTER TABLE comment DROP register_id');
        $this->addSql('ALTER TABLE register DROP FOREIGN KEY FK_5FF94014A76ED395');
        $this->addSql('DROP INDEX UNIQ_5FF94014A76ED395 ON register');
        $this->addSql('ALTER TABLE register DROP user_id');
    }
}
