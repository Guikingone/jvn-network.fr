set :application, "jvn-network"
set :domain,      "s601940205.onlinehome.fr"
set :deploy_to,   "/"
set :app_path,    "app"
set :shared_files, ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs", web_path + "/uploads", "vendor"]
set :use_composer, true
set :update_vendors, true

set :repository,  "file://C:/wamp/www/Lab_Dev/Projets_IP/JVN_Network/Symfony"
set :deploy_via,  :copy
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

set :dump_assetic_assets, true

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :use_sudo,       false
set  :keep_releases,  3

set :writable_dirs, ["app/cache", "app/logs", "app/sessions"]
set :webserver_user, "www-data"
set :permission_method, :acl
set :use_set_permissions, true

ssh_options[:forward_agent] = true
default_run_options[:pty] = true

before 'symfony:assetic:dump', 'bower:install'


# Be more verbose by uncommenting the following line
 logger.level = Logger::MAX_LEVEL
