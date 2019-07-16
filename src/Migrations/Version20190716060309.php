<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190716060309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `character` (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, modified DATE DEFAULT NULL, resource_uri VARCHAR(255) DEFAULT NULL, thumbnail_path VARCHAR(255) DEFAULT NULL, thumbnail_extension VARCHAR(255) DEFAULT NULL, picture_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_comic (character_id INT NOT NULL, comic_id INT NOT NULL, INDEX IDX_A5BD42C81136BE75 (character_id), INDEX IDX_A5BD42C8D663094A (comic_id), PRIMARY KEY(character_id, comic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comic (id INT NOT NULL, digital_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, issue_number INT DEFAULT NULL, variant_description VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, modified DATETIME DEFAULT NULL, isbn VARCHAR(255) DEFAULT NULL, format VARCHAR(255) DEFAULT NULL, page_count INT DEFAULT NULL, detail_url VARCHAR(255) DEFAULT NULL, purchase_url VARCHAR(255) DEFAULT NULL, onsale_date DATETIME DEFAULT NULL, digital_purchase_date DATETIME DEFAULT NULL, print_price DOUBLE PRECISION DEFAULT NULL, digital_purchase_price DOUBLE PRECISION DEFAULT NULL, thumbnail_path VARCHAR(255) DEFAULT NULL, thumbnail_extension VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creator (id INT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, suffix VARCHAR(255) DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, thumbnail_path VARCHAR(255) DEFAULT NULL, thumbnail_extension VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creator_comic (creator_id INT NOT NULL, comic_id INT NOT NULL, INDEX IDX_D46DB31461220EA6 (creator_id), INDEX IDX_D46DB314D663094A (comic_id), PRIMARY KEY(creator_id, comic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_comic ADD CONSTRAINT FK_A5BD42C81136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_comic ADD CONSTRAINT FK_A5BD42C8D663094A FOREIGN KEY (comic_id) REFERENCES comic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE creator_comic ADD CONSTRAINT FK_D46DB31461220EA6 FOREIGN KEY (creator_id) REFERENCES creator (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE creator_comic ADD CONSTRAINT FK_D46DB314D663094A FOREIGN KEY (comic_id) REFERENCES comic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD country VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE character_comic DROP FOREIGN KEY FK_A5BD42C81136BE75');
        $this->addSql('ALTER TABLE character_comic DROP FOREIGN KEY FK_A5BD42C8D663094A');
        $this->addSql('ALTER TABLE creator_comic DROP FOREIGN KEY FK_D46DB314D663094A');
        $this->addSql('ALTER TABLE creator_comic DROP FOREIGN KEY FK_D46DB31461220EA6');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE character_comic');
        $this->addSql('DROP TABLE comic');
        $this->addSql('DROP TABLE creator');
        $this->addSql('DROP TABLE creator_comic');
        $this->addSql('ALTER TABLE user DROP country, DROP email');
    }
}
