<?php
namespace Deployer;

require 'recipe/symfony3.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'https://github.com/gaineryns/majestics2.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('steveyongwo.com')
    ->stage('prod')
    ->user('defaultsteve')
    ->port(2222)
    ->configFile('~/.ssh/config')
    ->identityFile('~/.ssh/id_rsa')
    ->set('deploy_path', '/var/www/clients/client0/steveyongwo.com/web/');


// Tasks

task('build', function () {
    run('export APP_ENV=prod');
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

//before('deploy:symlink', 'database:migrate');

