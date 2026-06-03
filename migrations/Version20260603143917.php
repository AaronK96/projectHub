<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260603143917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subtask (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, is_completed TINYINT NOT NULL, position INT DEFAULT NULL, task_id INT NOT NULL, INDEX IDX_8BCBA9AE8DB60186 (task_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE subtask ADD CONSTRAINT FK_8BCBA9AE8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subtask DROP FOREIGN KEY FK_8BCBA9AE8DB60186');
        $this->addSql('DROP TABLE subtask');
    }
}
