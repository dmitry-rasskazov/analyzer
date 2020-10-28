<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201028220414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create tables and add first user';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE result (id SERIAL NOT NULL, user_id_id INT DEFAULT NULL, arr JSON NOT NULL, num INT NOT NULL, index INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_136AC1139D86650F ON result (user_id_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, auth_code BYTEA NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1139D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('INSERT INTO public.user (name, auth_code) VALUES (\'testUser\', \'ds23gkfj41sz6t5ghsdt4r135hr4\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP CONSTRAINT FK_136AC1139D86650F');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE "user"');
    }
}
