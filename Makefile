layout-backend-dist-dir = layout/backend/
layout-frontend-dist-dir = layout/frontend/

module-andreatta-dir = module/Andreatta/
module-admin-dir = module/Admin/
module-application-dir = module/Application/

test-remote-host = www-data@server.local.rafaa.me
test-remote-dir = /var/www/php-base-zf2.server.local.rafaa.me/
test-certificate-file = /Users/rafaame/Keys/www-data-server.local.rafaa.me

sync-all: sync-backend sync-frontend

sync-frontend:

	cd $(layout-frontend-dist-dir) && make sync

sync-backend:

	cd $(layout-backend-dist-dir) && make sync

build-all: build-backend build-frontend

build-frontend:

	cd $(layout-frontend-dist-dir) && make build

build-backend:
	
	cd $(layout-backend-dist-dir) && make build

test-andreatta:

	ssh -i $(test-certificate-file) $(test-remote-host) 'cd $(test-remote-dir)/$(module-andreatta-dir)/test/ && $(test-remote-dir)/vendor/bin/phpunit'

test-admin:

	ssh -i $(test-certificate-file) $(test-remote-host) 'cd $(test-remote-dir)/$(module-admin-dir)/test/ && $(test-remote-dir)/vendor/bin/phpunit'

test-application:

	ssh -i $(test-certificate-file) $(test-remote-host) 'cd $(test-remote-dir)/$(module-application-dir)/test/ && $(test-remote-dir)/vendor/bin/phpunit'

test: test-andreatta test-admin test-application