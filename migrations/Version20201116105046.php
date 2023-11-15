<?php

declare(strict_types=1);


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116105046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE wemea_menu (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, visibility INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6AF2E5DF77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wemea_menu_channels (menu_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_87D405F7CCD7E912 (menu_id), INDEX IDX_87D405F772F5A1AA (channel_id), PRIMARY KEY(menu_id, channel_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wemea_menu_item (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, target VARCHAR(10) NOT NULL, css_classes VARCHAR(255) DEFAULT NULL, priority INT DEFAULT NULL, INDEX IDX_81682814CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wemea_menu_item_image (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, path VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_389662D07E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wemea_menu_item_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_6A8B1F082C2AC5D3 (translatable_id), UNIQUE INDEX wemea_menu_item_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wemea_menu_link (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, product_id INT DEFAULT NULL, taxon_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_A8DF94FB7E3C61F9 (owner_id), INDEX IDX_A8DF94FB4584665A (product_id), INDEX IDX_A8DF94FBDE13F470 (taxon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wemea_menu_link_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, custom_link VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_4763CB6D2C2AC5D3 (translatable_id), UNIQUE INDEX wemea_menu_link_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wemea_menu_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, title VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_4F6A05772C2AC5D3 (translatable_id), UNIQUE INDEX wemea_menu_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wemea_menu_channels ADD CONSTRAINT FK_87D405F7CCD7E912 FOREIGN KEY (menu_id) REFERENCES wemea_menu (id)');
        $this->addSql('ALTER TABLE wemea_menu_channels ADD CONSTRAINT FK_87D405F772F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id)');
        $this->addSql('ALTER TABLE wemea_menu_item ADD CONSTRAINT FK_81682814CCD7E912 FOREIGN KEY (menu_id) REFERENCES wemea_menu (id)');
        $this->addSql('ALTER TABLE wemea_menu_item_image ADD CONSTRAINT FK_389662D07E3C61F9 FOREIGN KEY (owner_id) REFERENCES wemea_menu_item (id)');
        $this->addSql('ALTER TABLE wemea_menu_item_translation ADD CONSTRAINT FK_6A8B1F082C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES wemea_menu_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wemea_menu_link ADD CONSTRAINT FK_A8DF94FB7E3C61F9 FOREIGN KEY (owner_id) REFERENCES wemea_menu_item (id)');
        $this->addSql('ALTER TABLE wemea_menu_link ADD CONSTRAINT FK_A8DF94FB4584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE wemea_menu_link ADD CONSTRAINT FK_A8DF94FBDE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id)');
        $this->addSql('ALTER TABLE wemea_menu_link_translation ADD CONSTRAINT FK_4763CB6D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES wemea_menu_link (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wemea_menu_translation ADD CONSTRAINT FK_4F6A05772C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES wemea_menu (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE wemea_menu_channels DROP FOREIGN KEY FK_87D405F7CCD7E912');
        $this->addSql('ALTER TABLE wemea_menu_item DROP FOREIGN KEY FK_81682814CCD7E912');
        $this->addSql('ALTER TABLE wemea_menu_translation DROP FOREIGN KEY FK_4F6A05772C2AC5D3');
        $this->addSql('ALTER TABLE wemea_menu_item_image DROP FOREIGN KEY FK_389662D07E3C61F9');
        $this->addSql('ALTER TABLE wemea_menu_item_translation DROP FOREIGN KEY FK_6A8B1F082C2AC5D3');
        $this->addSql('ALTER TABLE wemea_menu_link DROP FOREIGN KEY FK_A8DF94FB7E3C61F9');
        $this->addSql('ALTER TABLE wemea_menu_link_translation DROP FOREIGN KEY FK_4763CB6D2C2AC5D3');
        $this->addSql('DROP TABLE wemea_menu');
        $this->addSql('DROP TABLE wemea_menu_channels');
        $this->addSql('DROP TABLE wemea_menu_item');
        $this->addSql('DROP TABLE wemea_menu_item_image');
        $this->addSql('DROP TABLE wemea_menu_item_translation');
        $this->addSql('DROP TABLE wemea_menu_link');
        $this->addSql('DROP TABLE wemea_menu_link_translation');
        $this->addSql('DROP TABLE wemea_menu_translation');
    }
}
