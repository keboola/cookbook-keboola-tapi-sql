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

cookbook_file "/tmp/register-mysql-server.php" do
    source "register-mysql-server.php"
    mode "0644"
    owner "root"
    group "root"
end

template "/etc/my.cnf" do
  source 'my.cnf.erb'
  owner 'root'
  group 'root'
  mode 00644
  variables({
    :innodb_buffer_pool_size => node['keboola-transformation-db']['mysql']['innodb-buffer-pool-size']
  })
end

directory "/media/ephemeral0/mysql" do
  owner "mysql"
  group "mysql"
  mode 00755
  action :create
end

directory "/var/lib/mysql" do
  recursive true
  action :delete
end

link "/var/lib/mysql" do
  to "/media/ephemeral0/mysql"
end

execute "create log dirs" do
    command "mkdir /var/log/mariadb"
end

execute "clean yum cache" do
  command "yum clean all"
end

execute "install mariadb" do
  command "yum -y install php MariaDB-server MariaDB-client"
end



execute "run mariadb" do
    command "/etc/init.d/mysql start"
end

execute "set default password for root user" do
    command "/usr/bin/mysqladmin -u root password '#{node['keboola-transformation-db']['mysql']['default-password']}'"
end

execute "allow ssh tunnel root access" do
    command "mysql -u root -p#{node['keboola-transformation-db']['mysql']['default-password']} -e \"SET PASSWORD FOR 'root'@'127.0.0.1' = PASSWORD('#{node['keboola-transformation-db']['mysql']['default-password']}');\""
end

execute "mysql tzinfo" do
    command "mysql_tzinfo_to_sql /usr/share/zoneinfo/ | mysql -u root -p#{node['keboola-transformation-db']['mysql']['default-password']} mysql"
end

execute "install mkpasswd" do
  command "yum -y install expect"
end

execute "prepare provisioning user and register provisioning server" do
    command "php /tmp/register-mysql-server.php --manage-api-url=\"#{node['keboola-transformation-db']['provisioning-manage-api']['url']}\" --manage-token=\"#{node['keboola-transformation-db']['provisioning-manage-api']['token']}\" --local-root-password=\"#{node['keboola-transformation-db']['mysql']['default-password']}\" --user=provisioning --database=provisioning --host=\"`wget -q -O - http://instance-data/latest/meta-data/public-hostname`\" --type=transformations --mode=active"
end
