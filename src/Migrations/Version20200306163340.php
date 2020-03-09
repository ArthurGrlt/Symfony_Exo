<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200306163340 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, deadline DATE NOT NULL, state TINYINT(1) NOT NULL, INDEX IDX_505865979D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, inscription DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865979D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_505865979D86650F');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE users');
    }
}
