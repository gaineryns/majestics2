<?php
namespace Deployer;

require 'recipe/symfony3.php';

// Project name
set('application', 'my_project');

set('env', [
    'APP_ENV' => 'prod',
]);



task('deploy:assets:install', function () {
});

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
    ->user('defaultmajestic')
    ->port(2222)
    ->set('deploy_path', '/var/www/clients/client0/web2/web');


// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

//before('deploy:symlink', 'database:migrate');

