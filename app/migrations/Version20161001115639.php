<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161001115639 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_5A8A6C8DF6BD1646');
        $this->addSql('ALTER TABLE article ADD type VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX idx_5a8a6c8df6bd1646 ON article');
        $this->addSql('CREATE INDEX IDX_23A0E66F6BD1646 ON article (site_id)');
        $this->addSql('DROP INDEX idx_5a8a6c8da76ed395 ON article');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_5A8A6C8DF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F6BD1646');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE article DROP type');
        $this->addSql('DROP INDEX idx_23a0e66a76ed395 ON article');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON article (user_id)');
        $this->addSql('DROP INDEX idx_23a0e66f6bd1646 ON article');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF6BD1646 ON article (site_id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}
