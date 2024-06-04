<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604120721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE former (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, sex VARCHAR(1) NOT NULL, birthdate DATE NOT NULL, town VARCHAR(50) NOT NULL, mail VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(50) NOT NULL, INDEX IDX_C24262812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE program (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, module_id INT NOT NULL, nb_days INT NOT NULL, INDEX IDX_92ED7784613FECDF (session_id), INDEX IDX_92ED7784AFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, former_id INT NOT NULL, category_id INT NOT NULL, title VARCHAR(100) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, nb_places INT NOT NULL, program_details LONGTEXT NOT NULL, INDEX IDX_D044D5D46C20F19 (former_id), INDEX IDX_D044D5D412469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, sex VARCHAR(1) NOT NULL, birthdate DATE NOT NULL, town VARCHAR(50) NOT NULL, mail VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_session (student_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_3D72602CCB944F1A (student_id), INDEX IDX_3D72602C613FECDF (session_id), PRIMARY KEY(student_id, session_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C24262812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED7784613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED7784AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D46C20F19 FOREIGN KEY (former_id) REFERENCES former (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE student_session ADD CONSTRAINT FK_3D72602CCB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_session ADD CONSTRAINT FK_3D72602C613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C24262812469DE2');
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED7784613FECDF');
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED7784AFC2B591');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D46C20F19');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D412469DE2');
        $this->addSql('ALTER TABLE student_session DROP FOREIGN KEY FK_3D72602CCB944F1A');
        $this->addSql('ALTER TABLE student_session DROP FOREIGN KEY FK_3D72602C613FECDF');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE former');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE program');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_session');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
