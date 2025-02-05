<?php

declare(strict_types=1);

namespace Wemea\SyliusMenuPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120103659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wemea_menu RENAME INDEX uniq_6af2e5df77153098 TO UNIQ_5621853E77153098');
        $this->addSql('ALTER TABLE wemea_menu_channels RENAME INDEX idx_87d405f7ccd7e912 TO IDX_93E1EEEACCD7E912');
        $this->addSql('ALTER TABLE wemea_menu_channels RENAME INDEX idx_87d405f772f5a1aa TO IDX_93E1EEEA72F5A1AA');
        $this->addSql('ALTER TABLE wemea_menu_item RENAME INDEX idx_81682814ccd7e912 TO IDX_53C6C3EECCD7E912');
        $this->addSql('ALTER TABLE wemea_menu_item_image RENAME INDEX uniq_389662d07e3c61f9 TO UNIQ_F02221097E3C61F9');
        $this->addSql('ALTER TABLE wemea_menu_item_translation RENAME INDEX idx_6a8b1f082c2ac5d3 TO IDX_4D456E432C2AC5D3');
        $this->addSql('ALTER TABLE wemea_menu_link RENAME INDEX uniq_a8df94fb7e3c61f9 TO UNIQ_7A717F017E3C61F9');
        $this->addSql('ALTER TABLE wemea_menu_link RENAME INDEX idx_a8df94fb4584665a TO IDX_7A717F014584665A');
        $this->addSql('ALTER TABLE wemea_menu_link RENAME INDEX idx_a8df94fbde13f470 TO IDX_7A717F01DE13F470');
        $this->addSql('ALTER TABLE wemea_menu_link_translation RENAME INDEX idx_4763cb6d2c2ac5d3 TO IDX_60ADBA262C2AC5D3');
        $this->addSql('ALTER TABLE wemea_menu_translation RENAME INDEX idx_4f6a05772c2ac5d3 TO IDX_B0ADDB442C2AC5D3');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wemea_menu RENAME INDEX uniq_5621853e77153098 TO UNIQ_6AF2E5DF77153098');
        $this->addSql('ALTER TABLE wemea_menu_channels RENAME INDEX idx_93e1eeeaccd7e912 TO IDX_87D405F7CCD7E912');
        $this->addSql('ALTER TABLE wemea_menu_channels RENAME INDEX idx_93e1eeea72f5a1aa TO IDX_87D405F772F5A1AA');
        $this->addSql('ALTER TABLE wemea_menu_item RENAME INDEX idx_53c6c3eeccd7e912 TO IDX_81682814CCD7E912');
        $this->addSql('ALTER TABLE wemea_menu_item_image RENAME INDEX uniq_f02221097e3c61f9 TO UNIQ_389662D07E3C61F9');
        $this->addSql('ALTER TABLE wemea_menu_item_translation RENAME INDEX idx_4d456e432c2ac5d3 TO IDX_6A8B1F082C2AC5D3');
        $this->addSql('ALTER TABLE wemea_menu_link RENAME INDEX uniq_7a717f017e3c61f9 TO UNIQ_A8DF94FB7E3C61F9');
        $this->addSql('ALTER TABLE wemea_menu_link RENAME INDEX idx_7a717f014584665a TO IDX_A8DF94FB4584665A');
        $this->addSql('ALTER TABLE wemea_menu_link RENAME INDEX idx_7a717f01de13f470 TO IDX_A8DF94FBDE13F470');
        $this->addSql('ALTER TABLE wemea_menu_link_translation RENAME INDEX idx_60adba262c2ac5d3 TO IDX_4763CB6D2C2AC5D3');
        $this->addSql('ALTER TABLE wemea_menu_translation RENAME INDEX idx_b0addb442c2ac5d3 TO IDX_4F6A05772C2AC5D3');
    }
}
