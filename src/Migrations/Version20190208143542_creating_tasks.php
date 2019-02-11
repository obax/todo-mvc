<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190208143542_creating_tasks extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE todo_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, due_date DATETIME DEFAULT NULL, creation_date DATETIME NOT NULL, is_completed TINYINT(1) NOT NULL, description VARCHAR (255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->addSql("INSERT into `todo_item` VALUES (1, 'Create a to-do item', null, now(), 0, 'Tutorial item 1')");
        $this->addSql("INSERT into `todo_item` VALUES (2, 'Put this in your todo for today', null, now(), 0, 'Tutorial item 2')");
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE todo_item');
    }
}
