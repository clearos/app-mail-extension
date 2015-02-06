
Name: app-mail-extension
Epoch: 1
Version: 2.0.18
Release: 1%{dist}
Summary: Mail Extension - Core
License: LGPLv3
Group: ClearOS/Libraries
Source: app-mail-extension-%{version}.tar.gz
Buildarch: noarch

%description
The Mail Extension extends the directory with user mail and alias attributes.

%package core
Summary: Mail Extension - Core
Requires: app-base-core
Requires: app-mail-core
Requires: app-openldap-directory-core
Requires: app-users

%description core
The Mail Extension extends the directory with user mail and alias attributes.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/mail_extension
cp -r * %{buildroot}/usr/clearos/apps/mail_extension/

install -D -m 0644 packaging/mail.php %{buildroot}/var/clearos/openldap_directory/extensions/71_mail.php
install -D -m 0644 packaging/mail_extension.conf %{buildroot}/etc/clearos/mail_extension.conf

%post core
logger -p local6.notice -t installer 'app-mail-extension-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/mail_extension/deploy/install ] && /usr/clearos/apps/mail_extension/deploy/install
fi

[ -x /usr/clearos/apps/mail_extension/deploy/upgrade ] && /usr/clearos/apps/mail_extension/deploy/upgrade

exit 0

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-mail-extension-core - uninstalling'
    [ -x /usr/clearos/apps/mail_extension/deploy/uninstall ] && /usr/clearos/apps/mail_extension/deploy/uninstall
fi

exit 0

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/mail_extension/packaging
%dir /usr/clearos/apps/mail_extension
/usr/clearos/apps/mail_extension/deploy
/usr/clearos/apps/mail_extension/language
/usr/clearos/apps/mail_extension/libraries
/var/clearos/openldap_directory/extensions/71_mail.php
%config(noreplace) /etc/clearos/mail_extension.conf
