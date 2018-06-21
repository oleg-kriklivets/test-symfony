<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180619170027 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE league (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT UNSIGNED AUTO_INCREMENT NOT NULL, league_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, strip VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61F58AFC4DE (league_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id) ON DELETE SET NULL');

        $this->addSql('INSERT INTO league  (`name`) VALUES
            (\'Premier League\'),
            (\'English Football League Championship\'),
            (\'English Football League One\'),
            (\'English Football League Two\'),
            (\'National League\'),
            (\'National League North\'),
            (\'National League South\'),
            (\'Football League Cup\')
        ');
        $this->addSql('SELECT id 
            INTO @id 
            FROM league 
            WHERE name = \'Premier League\'
        ');
        $strips = ['black', 'red', 'blue', 'brown', 'yellow', 'white'];
        $this->addSql('INSERT INTO team  (`name`, `strip`, `league_id`) VALUES
            (\'Arsenal\', \''.$strips[array_rand($strips)].'\', @id),
            (\'Bournemouth\', \''.$strips[array_rand($strips)].'\', @id),
            (\'Brighton\', \''.$strips[array_rand($strips)].'\', @id),
            (\'Burnley\', \''.$strips[array_rand($strips)].'\', @id),
            (\'Cardiff\', \''.$strips[array_rand($strips)].'\', @id),
            (\'Chelsea\', \''.$strips[array_rand($strips)].'\', @id),
            (\'Crystal Palace\', \''.$strips[array_rand($strips)].'\', @id),
            (\'Everton\', \''.$strips[array_rand($strips)].'\', @id),
            (\'Fulham\', \''.$strips[array_rand($strips)].'\', @id),
            (\'Huddersfield\', \''.$strips[array_rand($strips)].'\', @id)
        ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F58AFC4DE');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE team');
    }
}
