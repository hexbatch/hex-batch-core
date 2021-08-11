# HEX Batch docker tools and environment

All the tools and programs can be used inside docker, and there is a make file in this directory too, to automate some thing, like running migrations, or saving sql schema, or generating docs. To run this makefile from anywhere, use the C option to point how to get to this directory. For some instructions, Its important to run as sudo or else the generated folder will be set to root on many systems. Some docker installs will need to sudo to run at all

## Setup
* Install docker and docker-composer
* copy the .docker/.env.example to the .env if you do not have one, most of the settings there will not need to be changed as all the things are just designed to run on local
* run `make -C tools docker-up` to build all of them at once. It may take a while

DB and other settings are passed to php via environmental variables which are created from the .env file

If you change the environmental variables, need to run `make -C tools docker-build` before they take effect. You will need to do a docker-down and docker-up again after that to avoid issues (nginx is particularly stubborn about restarting)

if there is an issue with a container, then check the docker logs, using the name of the thing you want to track

	docker logs --tail 50 --follow --timestamps hexbatch_dev_backup_1
    
### Examples            

    sudo make -C tools  generate-docs
    
    ACTION="migrate" make -C tools sql-migrations


## Regular C++ Build via docker

* use the docker image **hexbatch_dev/cpp** and can be set to run with the command
    * `make -C tools docker-up CONTAINER=db`
* in the project settings, make new toolchain and do not need to do any changes other than put in the docker image, be sure to mark to terminate or else they will build up after each build    

## Emscripten

* use `docker pull emscripten/emsdk` to get the container to compile wasm
* compiled wasm and js target is now temporary put into the tools/wasm folder for display in the web 
* tool chain, copy the debug
  * in project setttings: c/c++ build, put the new chain you made
  * tool settings tab:
    * gcc++ compiler: put `emcc` as the command
    * gcc c++ linker: put `emcc  -o hexbatch-core.js` as the command
  * container settings tab:
    * do the image for the emscripten/emsdk:latest
  * build steps tab 
    * prebuild steps command: `./emconfigure ./configure;./emmake make`
  * build artifact tab:
    * extension is `js`  
    
    
##SQL And Migrations

###DB TOOLS

* MYSQL (or drop in Maria DB) is found in the docker image **hexbatch_dev/db** and can be set to run with the command
* `make -C tools docker-up CONTAINER=db`
    * MySQL Admin is run from the docker image **hexbatch_dev/phpmyadmin** and can be set to run with the command
* `make -C tools docker-up CONTAINER=phpmyadmin`
    * Migrations are Managed by the phinx container, which is part of the docker compose setup here
* `make -C tools docker-up CONTAINER=phinx`
  * To run migration commands:  
    * `sudo ACTION="migrate" make -C tools sql-migrations`
* To make a schema dump
     * `make -C tools copy-sql-schema`
* The db can be manually entered into by going into the running mysql container (whose state is preserved in the docker volume), using the command 
    * `docker exec -it hexbatch_dev_db_1 bash`
    

###DB and Docker

* When the docker db is first created, it uses the new authencation method, which php 7.3 does not recognize. To fix this log into the db terminal as root:
		    ```mysql
		    ALTER USER 'gokabam_hexlet'@'%' IDENTIFIED WITH mysql_native_password BY '2fancy4pants';
		    ```    
  
* to login into a db shell without entering docker
    * `mysql --port=3310  --host=127.0.0.1  -uroot -p --protocol=tcp`  

* Can always enter the db docker with an exec command, and run the mysql on the command line inside the container, if do not have mysql installed on host machine


### Importing and Exporting the db disk

When you need to save the entire db, or transfer it over to a computer there are a few options

#### Export to stdout
    docker run --rm -v $VOLUME:/data -w /data alpine tar czf - ./

#### Import from stdin
    docker run --rm -i -v $VOLUME:/data -w /data alpine tar xzf -

#### Backup to S3
    docker run --rm -v $VOLUME:/data -w /data alpine tar czf - ./ \
      | aws s3 cp - s3://mybucket/$VOLUME-`date +%s`.tar.gz

#### Restore from a local file
    docker run --rm -i -v $VOLUME:/data -w /data alpine tar xzf - \
      < myvolume.tar.gz

#### Transfer over SSH

 
		 ```shell
		    
		docker run --rm -v $VOLUME:/data -w /data alpine tar czf - ./ \
		  | ssh myhost.example.com \
		    "docker run --rm -i -v $VOLUME:/data -w /data alpine tar xzf -"

(If bandwidth is not a concern, remove the "z" from both tar commands to disable compression and potentially speed up the transfer.)
    
###Migrations


`sudo ACTION="migrate" make -C tools sql-migrations`

* sudo is needed on some setups as the generated file is made under root conditions 
* the command to the phinx goes in the action quotes. It can be any command
* [phinx documentation](https://book.cakephp.org/phinx/0/en/contents.html)
* The phinx setup is in the yaml file
* Migrations MUST be written in php, using the phinx library
* The phinx manages the migrations in the database/mysql/schema/migrations folder
* If there is any constant data to be put into the db, then it should be managed by phinx in the seeds 
    

# doxygen

The full documentation, and todo lists, etc are created by doxygen. When there is some sort of release, the docs are copied from the edocs to the docs, which is a sub repo, and a commit is made. Otherwise, the docs are not perminent 

To run doxygen, which is configured regenerate the documentation (refreshing the edocs folder which is not tracked by git)
	`sudo make -C tools  generate-docs`

If need to build seperately the doxygen container
	`make docker-build CONTAINER=doxygen`

The doxygen reads from the Doxyfile located in this directory, If this needs to be totally remade, then make config file by
`docker run -it --rm -v $PWD:/data hexbatch_dev/doxygen -g Doxyfile`

# webserver

When needed for tests, there is a full webserver 
	* `make docker-build CONTAINER=php-fpm`
	* `make docker-build CONTAINER=nginx` 
	
The port is set in the env to be whatever is needed: example localhost:8000
	
The document root is at htdocs/ in this folder


# backups

* using https://github.com/offen/docker-volume-backup
* configured to do automatically at 1am each day if the containers are running, will stop services while doing this
* manual command to do so at anytime is `docker exec hexbatch_dev_backup_1 backup`
* to restore (after downloading and untarring backup, there will be two folders, one for each backed up volumne, then picking one each:

		docker run -d \
		  --name backup_restore \
		  -v <name of docker volumn.:/backup_restore
		  alpine
		docker cp <location_of_your_unpacked_backup> backup_restore:/backup_restore
		docker stop backup_restore && docker rm backup_restore
		
		
* if, for some reason, you need to forcefully stop the backup container by itself, then `sudo docker rm --force hexbatch_dev_backup_1`		

     
        
# Useful Docker Commands

* if need to debug container that will not start, then make a new thing and enter it
   	 * `docker run -it --rm --entrypoint sh hexbatch_dev/phinx` 

* Opens up a bash shell inside the php or db container. For php, the default directory in the shell is mapped to the htdocs directory here
    * `sudo docker exec -it hexbatch_dev_php-fpm_1 bash`
    * `sudo docker exec -it hexbatch_dev_db_1 bash`

* when working with a stopped container, and you need to start it again and work with the shell
    * `sudo docker ps -a`  Find the container id
    * `sudo docker start e0ba1489ed37`  start it again (replace with the valid container id or name you want)
    * `sudo docker exec -it e0ba1489ed37 bin/bash` interact with it, you may need to change what you do here. This is for an ubuntu bash shell









