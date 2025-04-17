<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417084129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER url TYPE VARCHAR(500)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER image TYPE VARCHAR(500)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER title TYPE VARCHAR(500)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER description TYPE TEXT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER description TYPE TEXT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER date DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN article.date IS NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER url TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER image TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER title TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER description TYPE VARCHAR(2000)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ALTER date SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN article.date IS '(DC2Type:datetime_immutable)'
        SQL);
    }
}
