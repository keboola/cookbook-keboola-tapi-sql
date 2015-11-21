name             'keboola-tapi-sql'
maintainer       'YOUR_COMPANY_NAME'
maintainer_email 'YOUR_EMAIL'
license          'All rights reserved'
description      'Installs/Configures syrup'
long_description IO.read(File.join(File.dirname(__FILE__), 'README.md'))
version          '0.1.0'

depends 'aws', '~> 2.4.0'
depends 'keboola-common'
depends 'sysctl', '~> 0.6.0'
depends 'limits', '~> 1.0.0'
