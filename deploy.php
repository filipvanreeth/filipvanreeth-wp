<?php
namespace Deployer;

require 'recipe/common.php';

// Config
set('repository', 'git@github.com:filipvanreeth/filipvanreeth-wp.git');
set('keep_releases', 5);

add('shared_files', ['.env', 'web/.htaccess']);
add('shared_dirs', ['web/app/uploads']);
add('writable_dirs', ['web/app/uploads']);

// Hosts
host('combell')
    ->set('hostname', 'filipvanreeth-com')
    ->set('remote_user', 'filipvanreethcom')
    ->set('deploy_path', '/data/sites/web/filipvanreethcom/www')
    ->set('branch', 'main')
    ->setLabels([
        'environment' => 'production'
    ]);

host('cloudways')
    ->set('hostname', '161.35.153.12')
    ->set('remote_user', 'master_yvywrrwscf')
    ->set('deploy_path', '/home/master/applications/rncuxxuvyv/public_html')
    ->set('branch', 'main')
    ->setLabels([
        'environment' => 'production'
    ]);

// Tasks
desc('Install Composer dependencies');
task('deploy:vendors', function () {
    run('cd {{release_path}} && composer install --no-dev --quiet --prefer-dist --optimize-autoloader');
});

desc('Upload auth.json file');
task('deploy:upload_auth', function () {
    upload('auth.json', '{{release_path}}/auth.json');
});

// Hooks
before('deploy:vendors', 'deploy:upload_auth');
after('deploy:update_code', 'deploy:vendors');
after('deploy:failed', 'deploy:unlock');
