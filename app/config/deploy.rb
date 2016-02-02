set :application, "jvn-network"
set :domain,      "#{jvn-network}.fr"
set :deploy_to,   "/var/www/#{jvn-network.fr}"
set :app_path,    "app"
set :shared_files, ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs", web_path + "/uploads", "vendor"]
set :use_composer, true
set :update_vendors, true
set :deploy_via, :remote_cache

set :repository,  "#{git@github.com}:Guikingone/#{Symfony}.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
set :dump_assetic_assets, true
set :interactive_mode, false
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL
