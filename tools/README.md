# HEX Batch docker tools and environment

to run this makefile from anywhere, use the C option to point how to get to this directory
                
    sudo make -C tools  generate-docs

# Development backend

## Setup
* Install docker and docker-composer
* run `make docker-up` in the top directory of the project

DB and other settings are passed to php via environmental variables

### Docker commands

see config expanded with vars

    `docker-compose -f .docker/docker-compose.yml --project-directory .docker config`

### Code

Pull in code from repo and branch found in composer.json
Now uses the autoload psr-4 key in composer.json to include all library files. So composer is needed for both 
  adding third party libraries, and getting the hexlet library files loaded:
        
        composer install
        composer dump-autoload
        
Composer is auto installed into the Workspace constainer, just open a bash shell as discussed below to run composer command 
     
        


### License

Apache License v2        


### Importing and Exporting Test DB and Files

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
 (If bandwidth is not a concern, remove the "z" from both tar commands to disable compression and potentially speed up the transfer.)
 ```shell
    
docker run --rm -v $VOLUME:/data -w /data alpine tar czf - ./ \
  | ssh myhost.example.com \
    "docker run --rm -i -v $VOLUME:/data -w /data alpine tar xzf -"
   ```

## DB Management

### To login to a db shell

`mysql --port=3310  --host=127.0.0.1  -uroot -p --protocol=tcp`    
        
        
#Notes

* Added the v8js lib with prebuild v8 library, its copied and installed to all the php containers when they build

Docker Help Links
* Instructions for base container for the php is at https://github.com/docker-library/docs/tree/master/php
* List of easily to install php extensions is at https://github.com/mlocati/docker-php-extension-installer

useful commands:


*   Opens up a bash shell inside the php and environment of the container. The default directory in the shell is mapped to the code directory here
        
            sudo docker exec -it gokabam-hexlet_workspace_1 bash

when working with a stopped container, and you need to start it again and work with the shell
`sudo docker ps -a`  Find the container id
`sudo docker start e0ba1489ed37`  start it again (replace with the valid container id or name you want)
`sudo docker exec -it e0ba1489ed37 bin/bash` interact with it, you may need to change what you do here. This is for an ubuntu bash shell


#Hacks and Fixes

* When the docker db is first created, it uses the new authencation method, which php 7.3 does not recognize. To fix this log into the db terminal as root:
    ```mysql
    ALTER USER 'gokabam_hexlet'@'%' IDENTIFIED WITH mysql_native_password BY '123456';
    ```
  
  
#PHP Composer Dependencies 

Requires php extensions
* mysqli
* bcmath


# Branch Strategies

* Git branches will be named after the first three numbers in the version: so Version W.X.Y.Z will be on branch W.X.Z !
* Patches, the last number Z  will be the tag (so tag W.X.Y.Z) 
* Starting development out with branch 0.1.0 (need to start somewhere lol!)
* Will continue with 0.1.0 until first stage basic functionality is achieved 


# phinx migrations

Running Phinx, place the environment like this, and do not use phinx production or development modes

see [Migration Commands](https://book.cakephp.org/4/en/phinx/commands.html#migration-commands)
* `GOKABAM_ENVIRONMENT=development php vendor/bin/phinx create Object`
* `GOKABAM_ENVIRONMENT=development php vendor/bin/phinx migrate -e development`
* `GOKABAM_ENVIRONMENT=development php vendor/bin/phinx status`


# doxygen

build seperately (if needed)

sudo make docker-build CONTAINER=doxygen

make config file (if missing or need to redo)
`docker run -it --rm -v $PWD:/data hexbatch_dev/doxygen -g Doxyfile`

generate docs
 use full paths, and then change permissions. The make function generate_docs will do this below


    sudo docker run -it --rm \
    -v /home/will/cpdocs/hex-batch-core/tools:/data  \
    -v /home/will/cpdocs/hex-batch-core/src:/here_source_a  \
    -v /home/will/cpdocs/hex-batch-core/docs:/here_output \
    hexbatch_dev/doxygen

`sudo chown -R will:will /home/will/cpdocs/hex-batch-core/docs`


# useful links

* [Make manual](https://www.gnu.org/software/make/manual/make.html)





