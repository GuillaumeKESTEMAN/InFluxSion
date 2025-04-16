<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416095134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE source (id SERIAL NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users_sources (id SERIAL NOT NULL, user_id INT NOT NULL, source_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_225D1138A76ED395 ON users_sources (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_225D1138953C1C61 ON users_sources (source_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_sources ADD CONSTRAINT FK_225D1138A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_sources ADD CONSTRAINT FK_225D1138953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ADD source_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ADD CONSTRAINT FK_23A0E66953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_23A0E66953C1C61 ON article (source_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article DROP CONSTRAINT FK_23A0E66953C1C61
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_sources DROP CONSTRAINT FK_225D1138A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_sources DROP CONSTRAINT FK_225D1138953C1C61
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE source
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_sources
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_23A0E66953C1C61
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article DROP source_id
        SQL);
    }
}
