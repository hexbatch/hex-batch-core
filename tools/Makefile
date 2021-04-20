DOCKER_COMPOSE_DIR=./.docker
DOCKER_COMPOSE_FILE=$(DOCKER_COMPOSE_DIR)/docker-compose.yml
DEFAULT_CONTAINER=workspace
DOCKER_COMPOSE=docker-compose -f $(DOCKER_COMPOSE_FILE) --project-directory $(DOCKER_COMPOSE_DIR)


MK_PATH:=$(dir $(realpath $(lastword $(MAKEFILE_LIST))))
ROOT_FOLDER:=$(realpath $(MK_PATH)../)


SRC_FOLDER=$(ROOT_FOLDER)/src
DOCS_FOLDER=$(ROOT_FOLDER)/edocs
TOOLS_FOLDER=$(ROOT_FOLDER)/tools


SQL_FOLDER=$(ROOT_FOLDER)/database/mysql
SQL_ENV_FILE=$(ROOT_FOLDER)/tools/.docker/.env




    

DEFAULT_GOAL := help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

##@ [Docker] Build / Infrastructure
.docker/.env:
	cp $(DOCKER_COMPOSE_DIR)/.env.example $(DOCKER_COMPOSE_DIR)/.env

.PHONY: docker-clean
docker-clean: ## Remove the .env file for docker
	rm -f $(DOCKER_COMPOSE_DIR)/.env

.PHONY: docker-init
docker-init: .docker/.env ## Make sure the .env file exists for docker


.PHONY: docker-build-from-scratch
docker-build-from-scratch: docker-init ## Build all docker images from scratch, without cache etc. Build a specific image by providing the service name via: make docker-build CONTAINER=<service>
	$(DOCKER_COMPOSE) rm -fs $(CONTAINER) && \
	$(DOCKER_COMPOSE) build --pull --no-cache --parallel $(CONTAINER) && \
	$(DOCKER_COMPOSE) up -d --force-recreate $(CONTAINER)

.PHONY: docker-test
docker-test: docker-init docker-up ## Run the infrastructure tests for the docker setup
	sh $(DOCKER_COMPOSE_DIR)/docker-test.sh

.PHONY: docker-build
docker-build: docker-init ## Build all docker images. Build a specific image by providing the service name via: make docker-build CONTAINER=<service>
	$(DOCKER_COMPOSE) build --parallel $(CONTAINER) && \
	$(DOCKER_COMPOSE) up -d --force-recreate $(CONTAINER)

.PHONY: docker-prune
docker-prune: ## Remove unused docker resources via 'docker system prune -a -f --volumes'
	docker system prune -a -f --volumes

.PHONY: docker-up
docker-up: docker-init ## Start all docker containers. To only start one container, use CONTAINER=<service>
	$(DOCKER_COMPOSE) up -d $(CONTAINER)

.PHONY: docker-down
docker-down: docker-init ## Stop all docker containers. To only stop one container, use CONTAINER=<service>
	$(DOCKER_COMPOSE) down $(CONTAINER)


.PHONY: generate-docs
generate-docs: docker-init ## Generate the docs from Doxygen, puts them into the edocs folder, which is ignored. When you want save the docs, then update the docs subrepo
	 docker run -it --rm \
        -v $(TOOLS_FOLDER):/data  \
        -v $(ROOT_FOLDER):/top_root  \
        -v $(SRC_FOLDER):/src  \
        -v $(DOCS_FOLDER):/edocs \
        hexbatch_dev/doxygen; \
	chmod -R a+r $(DOCS_FOLDER)
	
	

.PHONY: sql-migrations
sql-migrations: docker-init ## migrate the mysql database, use ACTION="<action>" before make command
	docker run -it --rm \
	    --env-file /home/will/hexbatch/hex-batch-core/tools/.docker/.env \
		-v $(SQL_FOLDER):/app \
		--network hexbatch_dev_backend \
		hexbatch_dev/phinx $(ACTION); \
	chmod -R a+rw $(SQL_FOLDER)


