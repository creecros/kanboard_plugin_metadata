<?php

namespace Kanboard\Plugin\MetaMagik\Schema;

use PDO;

const VERSION = 7;

function version_7(PDO $pdo)
{
    $pdo->exec("ALTER TABLE metadata_types ADD COLUMN beauty_name VARCHAR(255) NOT NULL DEFAULT ''");
    $urq = $pdo->prepare('UPDATE metadata_types SET beauty_name=? WHERE id=?');
    $rq = $pdo->prepare('SELECT * FROM metadata_types ORDER BY id ASC');
    $rq->execute();
    foreach ($rq->fetchAll(PDO::FETCH_ASSOC) as $metadata_types) {
        $urq->execute(array(preg_replace('/_/', ' ', $metadata_types['human_name']), $metadata_types['id']));
    }

}

function version_6(PDO $pdo)
{
    $pdo->exec('ALTER TABLE metadata_types ADD COLUMN footer_inc BOOLEAN DEFAULT 0');
}


function version_5(PDO $pdo)
{
    $pdo->exec("UPDATE metadata_types SET attached_to = 0 WHERE attached_to IS NULL OR attached_to = 'task'");
}

function version_4(PDO $pdo)
{
    $pdo->exec('ALTER TABLE metadata_types ADD COLUMN column_number INTEGER DEFAULT 1');
}

function version_3(PDO $pdo)
{
    $pdo->exec('ALTER TABLE metadata_types ADD COLUMN position INTEGER DEFAULT 1');
    // Migrate all metadata_types position
    $position = 1;
    $urq = $pdo->prepare('UPDATE metadata_types SET position=? WHERE id=?');
    $rq = $pdo->prepare('SELECT * FROM metadata_types ORDER BY id ASC');
    $rq->execute();
    foreach ($rq->fetchAll(PDO::FETCH_ASSOC) as $metadata_types) {
        $urq->execute(array($position, $metadata_types['id']));
        $position++;
    }
}

function version_2(PDO $pdo)
{
    $pdo->exec('ALTER TABLE metadata_types ADD COLUMN options VARCHAR(255)');
}


function version_1(PDO $pdo)
{
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS metadata_types (
          id INTEGER PRIMARY KEY,
          human_name VARCHAR(255) NOT NULL,
          machine_name VARCHAR(255) NOT NULL,
          data_type VARCHAR(50) NOT NULL,
          is_required BOOLEAN DEFAULT 0,
          attached_to VARCHAR(50) NOT NULL,
          UNIQUE(machine_name, attached_to)
        )
    ');

    $pdo->exec('
        CREATE TABLE IF NOT EXISTS metadata_has_type (
          id INTEGER PRIMARY KEY,
          type_id INTEGER NOT NULL,
          metadata_id INTEGER NOT NULL,
          FOREIGN KEY(type_id) REFERENCES metadata_types(id) ON DELETE CASCADE
        )
    ');
}
