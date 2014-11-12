set :application, "chatserver"
set :domain,      "chatdeploy.allofzero.com"
set :deploy_to,   "/Users/dleute/Documents/IBM/chatdeploy"
set :app_path,    "app"

set :branch, "develop"
set   :scm,           :git
set   :repository,    "file:///Users/dleute/Documents/IBM/chatserver"
set :deploy_via, :rsync_with_remote_cache

set :copy_vendors, true
set :use_composer, false
set :update_vendors, false

set :writable_dirs,     ["app/cache", "app/logs"]
set :permission_method, :chown

#set   :deploy_via,    :copy
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
#set :shared_files,      ["app/config/parameters.yml"]
#set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]

# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set   :use_sudo,      false
set  :keep_releases,  3

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL