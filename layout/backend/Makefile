application-dir = ../../
layout-backend-dist-dir = httpdocs/admin/

sync-build = true
remote-host = www-data@server.local.rafaa.me
remote-dir = /var/www/php-zf2-base.server.local.rafaa.me/$(layout-backend-dist-dir)/
certificate-file = /Users/rafaame/Keys/www-data-server.local.rafaa.me
rsync-exclude = --exclude '.svn' --exclude '.git' --exclude '.hg' --exclude '.DS_Store'

sync: build

ifeq ($(sync-build),true)

	#Sync to remote host
	rsync -rtvz $(rsync-exclude) -e "ssh -i $(certificate-file)" css/ $(remote-host):$(remote-dir)/css/
	rsync -rtvz $(rsync-exclude) -e "ssh -i $(certificate-file)" snd/ $(remote-host):$(remote-dir)/snd/

endif

build:
	
	compass compile

	mkdir -p $(application-dir)/$(layout-backend-dist-dir)/css/
	mkdir -p $(application-dir)/$(layout-backend-dist-dir)/snd/

	cp -R css/* $(application-dir)/$(layout-backend-dist-dir)/css/
	cp -R snd/* $(application-dir)/$(layout-backend-dist-dir)/snd/