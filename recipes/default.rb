#
# Cookbook Name:: keboola-syrup
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
