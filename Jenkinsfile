pipeline {
  environment { 
    GIT_URL = "git@150.129.147.154:web/laravel/tacpharm.git"
    PROJECT_NAME = "tacpharm"
    PROJECT_SUFFIX = "web"
    SERVER_SSH_CRED = "contabo-development-key"
    ANSIBLE_EXEC_PATH_NAME = "ansible"
    PLAYBOOK_PATH = "ansible/app_deploy.yml"
    INVENTORY_PATH = "ansible/hosts"
    PROJECT_PATH = "/var/www/html"
    GIT_SSH_PRIVATE_KEY_PATH = "/home/developer/.ssh/development_git"
    SONAR_HOST = "http://172.16.10.158:9005"
    SONARQUBE_KEY = "Jenkins-SonarQube-Key"
    GIT_COMMIT_MSG = sh (script: """git log -1 --pretty=%B ${GIT_COMMIT}""", returnStdout:true).trim()
    MS_TEAMS_WEBHOOK_URL = "webhook_url_web_channel"
  }
  agent any
  options {
        ansiColor('xterm')
        buildDiscarder logRotator(artifactDaysToKeepStr: '', artifactNumToKeepStr: '', daysToKeepStr: '30', numToKeepStr: '15')
        disableConcurrentBuilds()
        timeout(time: 30, unit: 'MINUTES')
  }
  tools {nodejs "NodeJs_16"}
   stages {
    stage('Options') {
      steps {
        script{
          try {
            echo "** Do you want to run pipeline with interactive inputs? **"
            timeout(time:30, unit:'SECONDS') {
              env.OPTIONS = input message: "Pipeline with interactive inputs",
              parameters: [choice(name: 'Do you want to run pipeline with interactive inputs?', choices: 'no\nyes', description: 'Choose "yes" to get input options')]
            }
            } catch(err){
            env.OPTIONS = 'no'
          }
          if (OPTIONS == 'yes'){
          try {
            echo "** Do you want activate maintenance mode?**"
            timeout(time:30, unit:'SECONDS') {
              env.MAINTENANCE_MODE = input message: "Maintenance Mode Activation",
              parameters: [choice(name: 'Do you want to activate maintenance mode?', choices: 'enable\ndisable', description: 'Choose "enable" to activate maintenance mode.')]
            }
            }catch(err){
            env.MAINTENANCE_MODE = 'enable'
          }
          try {
            echo "** please confirm fresh migration command:  **"
            echo "=======> Do you want to run command: php artisan migration:fresh --seed? If yes then data will be wiped."
            timeout(time:30, unit:'SECONDS') {
              env.MIGRATION_COMMAND_INPUT = input message: "Do you want to wipe data?",
              parameters: [choice(name: 'Do you want to run Fresh Migration?', choices: 'no\nyes', description: 'Choose "yes" if you want to run command')]
            }
            echo "Fresh Migration Selection: ${env.MIGRATION_COMMAND_INPUT}"
          }catch(err){
            env.MIGRATION_COMMAND_INPUT = 'no'
          }
          try {
            echo "** Do you want to run composer update?**"
            timeout(time:30, unit:'SECONDS') {
              env.COMPOSER_COMMAND_INPUT = input message: "Composer update confirmation",
              parameters: [choice(name: 'Do you want to run composer update command?', choices: 'no\nyes', description: 'Choose "yes" if you want to run command')]
            }
          }catch(err){
            env.COMPOSER_COMMAND_INPUT = 'no'
          }
          try {
            echo "=========> Do you want to add environment variables in .env file?"
            timeout(time:30, unit:'SECONDS') {
              env.ENV_FILE = input message: "Add environment variables file",
              parameters: [choice(name: 'Do you want to add new env variables file?', choices: 'no\nyes', description: 'Choose "yes" to add file')]
            }
            if("${env.ENV_FILE}" == 'yes'){
              echo "=========> Please enter environment variables."
              timeout(time:30, unit:'SECONDS') {
              env.userInputTxt = input(
              id: 'inputTextbox',
              message: 'Paste All environment variables to be added in .env file',
              parameters: [
                [$class: 'TextParameterDefinition', description: 'Environment Variables',name: 'input']
              ])
              }
            }
        }catch(err){
          env.ENV_FILE = 'no'
          env.userInputTxt = 'null'
            }
          }
          else {
             env.MAINTENANCE_MODE = 'enable'
             env.MIGRATION_COMMAND_INPUT = 'no'
             env.COMPOSER_COMMAND_INPUT = 'no'
             env.ENV_FILE = 'no'
             env.userInputTxt = 'null'
          }
        }
        echo "Maintenance mode: ${env.MAINTENANCE_MODE}, Fresh Migration: ${env.MIGRATION_COMMAND_INPUT}, Composer Update: ${env.COMPOSER_COMMAND_INPUT}, Environment File Add: ${env.ENV_FILE}"
      }
    }
    stage('CodeQuality Check via SonarQube') {
          steps {
            catchError(buildResult: 'UNSTABLE', stageResult: 'FAILURE'){
              withSonarQubeEnv(credentialsId: "${SONARQUBE_KEY}", installationName: 'Codiant SonarQube') {
              script {
              def gradleHome = tool 'GradleOnline';
              def scannerHome = tool 'SonarOnline';
              withSonarQubeEnv("Codiant SonarQube") {
              sh "${tool("SonarOnline")}/bin/sonar-scanner -X \
              -Dsonar.projectKey=${env.PROJECT_NAME}-${env.PROJECT_SUFFIX}:${env.BRANCH_NAME} \
              -Dsonar.host.url=${env.SONAR_HOST} -Dsonar.exclusions='database/seeders/CitiesTableSeeder.php'"
                }
              }
            }
          }
          }
    }
    stage('Deploy') {
      steps {
        ansiblePlaybook(
          credentialsId: "${env.SERVER_SSH_CRED}",
          installation: "${env.ANSIBLE_EXEC_PATH_NAME}",
          playbook: "${env.PLAYBOOK_PATH}",
          inventory: "${env.INVENTORY_PATH}",
          colorized: true,
          extraVars: [
            git_repo: "${env.GIT_URL}",
            project_path: "${env.PROJECT_PATH}",
            project_name: "${env.PROJECT_NAME}-${env.BRANCH_NAME}",
            git_branch: "${env.BRANCH_NAME}",
            key_file: "${env.GIT_SSH_PRIVATE_KEY_PATH}",
            migration_command_input: "${env.MIGRATION_COMMAND_INPUT}",
            maintenance_mode: "${env.MAINTENANCE_MODE}",
            composer_update_input: "${env.COMPOSER_COMMAND_INPUT}",
            envfile_content: "${env.userInputTxt}",
            envfile_option: "${env.ENV_FILE}"
         ]
        )
      }
    }
  }

  post {
      always {
          withCredentials(bindings: [string(credentialsId: "${MS_TEAMS_WEBHOOK_URL}", variable: 'TEAMS_WEBHOOK_URL_CPANEL')]) {
              office365ConnectorSend webhookUrl: "${TEAMS_WEBHOOK_URL_CPANEL}",
              color: "${currentBuild.currentResult} == 'SUCCESS' ? '00ff00' : 'ff0000'",
              factDefinitions:[
                [ name: "Commit Message", template: "${env.GIT_COMMIT_MSG}"]
              ]
              }
        }
    }
}