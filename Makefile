#! bin/make


migrate:
	./bin/migrations migrations:migrate --db-configuration ./bin/doctrine-conf.php --configuration ./bin/migrations-conf.php