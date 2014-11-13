set :application, "chatserver"
set :domain,      "chatdeploy.allofzero.com"
set :deploy_to,   "/Users/dleute/Documents/IBM/chatdeploy"
set :app_path,    "app"
set :web_path,    "web"
set :php_bin,     "/opt/local/bin/php"
set :user,        "dleute"

set :branch, "develop"
set   :scm,           :git
set   :repository,    "file:///Users/dleute/Documents/IBM/chatserver"

set :copy_vendors, true
set :use_composer, true
set :update_vendors, false

set :writable_dirs,     ["app/cache", "app/logs"]
set :permission_method, :chown
set :set_permissions, false
set :webserver_user,  "_www"

set   :deploy_via,    :rsync_with_remote_cache

set :model_manager, "doctrine"
set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads"]

# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set   :use_sudo,      false
set  :keep_releases,  3

task :upload_parameters do
  origin_file = "app/config/parameters.yml"
  destination_file = shared_path + "/app/config/parameters.yml" # Notice the
  shared_path

  try_sudo "mkdir -p #{File.dirname(destination_file)}"
  top.upload(origin_file, destination_file)
end

after "deploy:setup", "upload_parameters"

task :fix_permissions do
  run "#{try_sudo} chmod +a \"#{user} allow delete,write,append,file_inherit,directory_inherit\" #{release_path}/#{app_path}/cache #{release_path}/#{app_path}/logs; true"
  run "#{try_sudo} chmod +a \"#{user} allow delete,write,append,file_inherit,directory_inherit\" #{shared_path}/#{app_path}/logs; true"
  run "#{try_sudo} chmod +a \"#{webserver_user} allow delete,write,append,file_inherit,directory_inherit\" #{release_path}/#{app_path}/cache #{release_path}/#{app_path}/logs; true"
  run "#{try_sudo} chmod +a \"#{webserver_user} allow delete,write,append,file_inherit,directory_inherit\" #{shared_path}/#{app_path}/logs; true"
#  run "#{try_sudo} chmod +a \"#{webserver_user} allow delete,write,append,file_inherit,directory_inherit\" #{current_path}/#{app_path}/cache #{release_path}/#{app_path}/logs"
#  run "#{try_sudo} chmod +a \"#{user} allow delete,write,append,file_inherit,directory_inherit\" #{release_path}/#{app_path}/cache #{release_path}/#{app_path}/logs"
#  run "#{try_sudo} chmod +a \"#{webserver_user} allow delete,write,append,file_inherit,directory_inherit\" #{release_path}/#{app_path}/cache #{release_path}/#{app_path}/logs"
end

after "symfony:composer:get", "fix_permissions"

task :restart_server do
  run "kill -9 $(lsof -i:5555 -t); true"

  run("(/usr/bin/env nohup #{php_bin} #{current_path}/#{app_path}/console --env=prod chat:server 8080 5555 &) && sleep 1", :pty => true)
end

after "deploy:create_symlink", "restart_server"

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL