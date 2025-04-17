<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417061506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE users_articles (id SERIAL NOT NULL, user_id INT NOT NULL, article_id INT NOT NULL, has_viewed BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C49C1AB2A76ED395 ON users_articles (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C49C1AB27294869C ON users_articles (article_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_articles ADD CONSTRAINT FK_C49C1AB2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_articles ADD CONSTRAINT FK_C49C1AB27294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_articles DROP CONSTRAINT FK_C49C1AB2A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users_articles DROP CONSTRAINT FK_C49C1AB27294869C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users_articles
        SQL);
    }
}
