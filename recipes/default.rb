#
# Cookbook Name:: keboola-syrup
# Recipe:: default
#

include_recipe "aws"
include_recipe "keboola-common"
include_recipe 'sysctl::apply'
