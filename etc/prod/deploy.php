<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            // SSH connection string to connect to the remote server (format: user@host-or-IP:port-number)
            ->server('defaultsteve@steveyongwo.com:2222')
            // the absolute path of the remote server directory where the project is deployed
            ->deployDir('web/')
            // the URL of the Git repository where the project code is hosted
            ->repositoryUrl('git@github.com:gaineryns/majestics2.git')
            // the repository branch to deploy
            ->repositoryBranch('master')

        ;
    }

    // run some local or remote commands before the deployment is started
    public function beforeStartingDeploy()
    {
        // $this->runLocal('./vendor/bin/simple-phpunit');
    }

    // run some local or remote commands after the deployment is finished
    public function beforeFinishingDeploy()
    {
        // $this->runRemote('{{ console_bin }} app:my-task-name');
        // $this->runLocal('say "The deployment has finished."');
    }
};
