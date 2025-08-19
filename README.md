# IUT Laravel
This repository is a starter giving you all tools you need to help you build your application with [Laravel](https://laravel.com) and PHP.

> Since this repository is for learning purpose, it is **not production ready**

## Requirements
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Visual Studio Code](https://code.visualstudio.com/download) with the [Docker](https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-docker) and the [DevContainers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers) extensions
- Git

### Windows Requirements
- [WSL 2](https://learn.microsoft.com/fr-fr/windows/wsl/install)
- Ubuntu 24.04 LTS installed from the [Microsoft Store](https://apps.microsoft.com/detail/9nz3klhxdjp5?hl=fr-fr&gl=FR)
- [WSL](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl) extension for Visual Studio Code
- [Optional] [Windows Terminal](https://apps.microsoft.com/detail/9n0dx20hk701?hl=fr-FR&gl=FR)

## Init project

### Prepare files and directory
Clone this repository (dnvn-iut/boilerplate.git) and rename the it using the git clone command

```sh
git clone git@gitlab.com:dnvn-iut/boilerplate.git iut-weather
```

Then, delete the hidden `.git` directory and `.gitlab-ci.yml` file.

> On Windows, make sure to clone this repository in the WSL 2 Ubuntu 24.04 distro.

### Create the environment
You have two ways to create the environment:
- Dev Containers: easier but require the [Microsoft Dev Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers) extension
- Docker Compose: common but need to play with differentes terminals and export some data on Windows / Linux

> If you cannot install the Dev Containers extension, you have to use the Docker Compose way.

#### Windows / Linux specifities
#### Dev Containers
Open the project with VSCode and a notification should appear to ask you if you want to re-open in a container. 
Say yes and you should have a new window with an empty explorer.

If there is no notification, you can launch it using the command palette and selecting `> Dev Containers: Rebuild and Reopen in Container`.

> Using this way, your VSCode is directly in the project directory of your project

#### Docker Compose
You can now start the environment from your own repository using Docker Compose with the following command line from the project root

```shell
make up
```

> This command line **MUST** be launched from your host using the Visual Studio Code **integrated terminal**

> **Windows users**: open the project using the WSL extension

Once the command has been launched, you should see a container stack called `weather` in Docker Desktop 

Finally, you can access the container terminal using the Visual Studio command palette and selecting

```shell
> Docker Containers: Attach Shell > weather > donovanbroquin/iut:latest
```

## Create the Laravel project
The Laravel project will be created using the Laravel installer Composer package which is already present in the Docker image.

For this application, you will use the raw approach with [Blade](https://laravel.com/docs/12.x/blade) for view rendering, [SQLite](https://sqlite.org) as database and [Pest](https://pestphp.com) as tests framework

```sh
laravel new weather --no-authentication --pest --npm --database=sqlite
```

Once it’s done, the app should be available at [http://localhost:8222](http://localhost:8222)
Congratulations 🎉 You can now start build your app!

### Interact with the app
Ensure to be in `/app/weather` to launch php / composer commands (*php artisan*, ... ) via `cd weather`

> The image use Bun as Node / NPM replacement. To make it simple, Bun as been aliased as npm

### Create a GitLab repository
Create a project named `iut-weather` in your own GitLab and follow the repository instructions to push project and make sure it is **public**.

## Services
In Docker Desktop, you can see three containers in the `weather` stack.
- app: this is where you application live
- valkey: a [Redis](https://redis.io/fr/) fork that will be used for cache / queues
- mailpit: will be used later too for email development and can be accessed at [127.0.0.1:8225](http://127.0.0.1:8225)

## Terminals
To keep peace in your mind, you need to remember there is many terminals to use and each one has a specific purpose.

- system: use it to interact with Git, mount containers. For Windows user, this terminal is the one running in the Ubuntu 24.04 distro
- container: use it to interact with the app and PHP. Ex: `php artisan`, `composer`, ... This one is launched using the VSCode Docker plugin

### Open the container terminal
VSCode with the Docker plugin make it easy to open a terminal within a container by using the command palette and using the `> Docker Containers: Attach Shell` shortcut

To open the command palette, you can use the following keyboard shortcuts
- macOS: **CMD+SHIFT+P**
- Windows: **FN+F1**

![Visual Studio Code open container terminal](https://github.com/dbroquin/iut-laravel/blob/624b5e34d1e5cc41b9e5f9c7262f9247e28c5e92/screenshots/vscode-open-container-terminal.mov?raw=true)

### Which terminal?
In VSCode, those terminals can be differentiated by their name and icons.
In the following screenshot, the system terminal is the `zsh` one in the right column and the container one, the `Shell: laravel task`.

![Visual Studio Code terminals.](https://github.com/dbroquin/iut-laravel/blob/624b5e34d1e5cc41b9e5f9c7262f9247e28c5e92/screenshots/vscode-terminals.png?raw=true)

> The system terminal name can be different for you according to your environment. Keep in mind the Docker container one will have Shell prefix and a spinner at end
