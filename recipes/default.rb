#
# Cookbook Name:: keboola-tapi-mysql
# Recipe:: default
#

include_recipe "aws"
include_recipe "keboola-common"
include_recipe 'sysctl::apply'


cookbook_file "/etc/yum.repos.d/MariaDB.repo" do
    source "MariaDB.repo"
    mode "0644"
    owner "root"
    group "root"
end

execute "clean yum cache" do
  command "yum clean all"
end

execute "install mariadb" do
  command "yum -y install MariaDB-server MariaDB-client"
end

cookbook_file "/etc/my.cnf" do
    source "my.cnf"
    mode "0644"
    owner "root"
    group "root"
end

execute "create log dirs" do
    command "mkdir /var/log/mariadb"
end

execute "run mariadb" do
    command "/etc/init.d/mysql start"
end

execute "set default password for root user" do
    command "/usr/bin/mysqladmin -u root password '#{node['keboola-transformation-db']['mysql']['default-password']}'"
end
