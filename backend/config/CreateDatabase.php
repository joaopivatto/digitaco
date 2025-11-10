<?php

namespace backend\config;

require_once __DIR__. 'TraitQueryExecution.php';

class CreateDatabase
{
    use \backend\config\TraitQueryExecution;
}

CreateDatabase::createDatabase();
CreateDatabase::useDatabase();
CreateDatabase::createTableUsers();
CreateDatabase::createTableLeagues();
CreateDatabase::createTableMatches();
CreateDatabase::createTableLeagueUser();
CreateDatabase::createTableLeagueLanguages();