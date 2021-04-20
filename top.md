[TOC]

# HexBatch Main Page

current version is pre-alpha-0.5.0

## Todo docker

### EMS

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
    
    
###SQL Migrations

  * Managed by the phinx container, which is part of the docker compose setup here
  * `sudo ACTION="migrate" make -C tools sql-migrations`
  * the command to the phinx goes in the action quotes
  * [phinx documentation](https://book.cakephp.org/phinx/0/en/contents.html)
  * Migrations MUST be written in php, using the phinx library
  * The phinx manages the migrations in the database/mysql/schema/migrations folder
  * The db can be manually entered into by going into the running mysql container (whose state is preserved in the docker volume), using the command 
    * `docker exec -it hexbatch_dev_db_1 bash`
  * To make a db dump `make -C tools copy-sql-schema`
    
  
  @todo new make task to dump the db schema to the schema.sql    









@todo get mongodb and sql-lite running as different containers

@todo update mysql,phpmysadmin to use the latest and best

@todo add in git modules for parts of the website, run by the docker nginx, add the shell game and the front page

@todo have a self signed ssl cert for localhost in the .env.example file and folder

@todo add in a testing suit I like as a container and be able to hook it up to the automated tests

## Todo git commit chain

@todo when doing any sort of commit, rebuild the docs using the `sudo make -C tools  generate-docs`


@todo when ready to make a push to a protected branch [alpha,beta,release,master] and not just a mealy mouthed topic partial push,
have tests run on source (unit + integrated) if any fail do not allow that push

@todo figure out a decent version path 
(do I just read it from the package.json when building and put it in a constant in the code , and use the same thing elewhere?)

@todo build the docker image of this library from current source and run integration tests on that also

@todo share the ide settings for the tool chain builds as they will depend on docker and have settings for the different outputs

@todo automate changing the tags of pre-alpha, alpha, beta , and current-release when pushing those branches to origin


## Todo building SELF Docker Image !

@todo Have docker image for running the built library! There should also be a docker images for the three different builds (standard, mongo, wasm)

in the tools/ one of the docker containers should be running this library , and the library should be the one just built
it should use the source code

@todo integeration tests should be run again inside the docker container as part of its build step, these are the tests associated with that version it built


## Shared Settings

@todo CLion IDE settings : need to share build settings and to-do lists and spelling

@todo spelling in dictionaries should be shared too


# Conventions
\section HexbatchConventions  HexBatch Conventions!

## Source Code

Any tool needed by this project must be from a container, to make it easy for anyone to have a full environment up and running

all source file/folder, and test file/folder names use underscore and not dash ! Sad, I know


all header and source file names have the folder path in the first part of their names, and the regular name as the last part, regular name is one word:
example: hexbatch_api_main.h  (will be a header at the top of the src/hexbatch/api)
hexbatch_api_topsoil_fungly.cpp ( will be  src/hexbatch/api/topsoil/hexbatch_api_topsoil_fungly.cpp )
Class names are same as file names, except snake case in place of underscores
Namespaces are same as file names when needed
the top namespace is hexbatch, the root of the sources

unit tests need to have .test. as the last part of their name, before the extension

integrated tests (like when testing the library response in its api) are in the tests folder and tests for hexbatch are in that folder
each integrated test should be in a folder of the api family, then the folder for the name of the api call, then named for what they try to do
example: tests/hexbatch/user_accounts/get_default_sudo/get_guid.xpp


all super secret passwords and certs will be in the .env , add in ability to store longer things (like ssl certs in a .env folder!)


## Git

git branches that are protected: alpha/*, beta/*, master, release/*

the alpha, beta and release branches share the same version number as the name so alpha/1.0.1.x beta/1.0.1.x release/1.0.1.x

### Alpha 

new alpha branch is spun off from master it has the current version of 3 plus a 0 for x , so 1.0.1.0

issues solved in alpha are done in topic branches, then put in master, and then new alpha branch is made with x bumped up so alpha/1.0.1.1.

Old alpha branch of 1.0.1.0 is deleted off origin


### Beta

Once the current alpha branch looks stable enough a new beta branch is spun off that alpha, and has the same version of 4, so beta/1.0.1.1

Bugs will be found in beta. When, during beta testing issues are fixed in topics, then they are pushed to master and not to this branch. 

And a new alpha branch is made with x (2 for example) bumped up again (and the older alpha removed)

the new beta branch from the fixes in alpha branch is created (so now beta/1.0.1.2 is here and the older beta/1.0.1.1 is removed from origin)

Keep cycling like that until we get a beta that passes ok (so for example beta/1.0.1.23)

### Release

when release is ready, the release branch is spun off from the beta branch , made release/1.0.1.23 and its distributed and the master branch has that tag now at the last commit

The release branch’s lifetime ends when its replaced by another release.

### Rules to live by:

only one alpha, beta, release branch exists at the same time

the tags in master are made to mark where each alpha , beta and release was spun off at (with same name as branch, I think?)

there is a constantly updated tag on master that points to the most current alpha, beta, and release and they are named just with that word

commits to master must pass all unit tests, and integrated source tests, and have its docker image of the library for current source pass its integration tests

#### Pre Alpha ignores these
pre-alpha branches escape all of these rules, as development is too fast and chaotic for all this nonsense of well established work flow.

Chaos rules!

With one important exception: any commit to the master branch must pass tests and be able to build a container that passes tests.

This means no updates to master until we have tests actually existing (or at least one!) and a container that actually works with the current version (such is) library

There can be multiple pre-alpha branches, but not more than three at any time. Also there needs to be a current tag called pre-alpha



## List of Resources

### Doxygen
* [doxygen](https://www.doxygen.nl/manual/autolink.html)
    * [Examples with how to do some page things](http://www.gerald-fahrnholz.eu/sw/DocGenerated/HowToUse/html/group___grp_about_this_doc_link_pages.html)
    * [Examples with some other things](https://root.cern/for_developers/doxygen/)
    * [Making different todo lists](https://www.doxygen.nl/manual/commands.html#cmdxrefitem)
    * [DOxygen Config file stuff](https://www.doxygen.nl/manual/config.html)
    * [Mark down in DOxygen](https://www.doxygen.nl/manual/markdown.html)
    




### Emscripten

* [emscripten](https://emscripten.org/docs/porting/connecting_cpp_and_javascript/Interacting-with-code.html)
* [step by step instructions to compile and link by hand](https://medium.com/@tdeniffel/pragmatic-compiling-from-c-to-webassembly-a-guide-a496cc5954b8)
* [very old hint how to do this for toolchain](https://emscripten-discuss.narkive.com/bQqV420g/emscripten-and-eclipse)
* [how to use it in large project](https://emscripten.org/docs/compiling/Building-Projects.html#integrating-with-a-build-system)
* [docker image](https://github.com/trzecieu/emscripten-docker)
* [offical docker image, will use version EMSCRIPTEN_VERSION=2.0.16](https://hub.docker.com/r/emscripten/emsdk)
* [old mailing list](https://emscripten-discuss.narkive.com/)

### Database

#### OBDC
* [OBDC library](https://nanodbc.github.io/nanodbc/)
    * Turn unicode options ON
* [Linux OBDC](http://www.iodbc.org/dataspace/doc/iodbc/wiki/iodbcWiki/WelcomeVisitors)

#### Mysql
* [Mysql and SLQLite Migrations (php)] (https://github.com/cakephp/phinx)

#### SQL Lite
* [SQL Lite ](https://github.com/sql-js/sql.js/)
* For when using on phones and browsers

#### Mongo

* [Mongo Migrations (node)](https://softwareontheroad.com/database-migration-node-mongo/)
* [Mongo driver for c++](https://github.com/mongodb/mongo-cxx-driver)
* [Mongo Dump](https://docs.mongodb.com/database-tools/mongodump/)




### Json
* [Mysql uses draft 4 of the json schema](https://json-schema.org/learn/getting-started-step-by-step.html)
    * so can enforce minimal json structures


### Swagger

swagger I am not using right now but keep this for later

* [Swagger JS Doc](https://github.com/Surnet/swagger-jsdoc)


### Conventions
*[google](https://google.github.io/styleguide/cppguide.html)

### Eclipse Tooling
*[Running Built inside a container](https://www.eclipse.org/community/eclipse_newsletter/2017/april/article1.php)
*[More older but helful instructions](https://jaxenter.com/docker-tooling-in-eclipse-2-124200.html)
* [general cdt faq](https://wiki.eclipse.org/CDT/User/FAQ)







