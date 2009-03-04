%define name callback_daemon
%define version 1.0.prod_r1528
%define unmangled_version 1.0.prod-r1528
%define unmangled_version 1.0.prod-r1528
%define release 1

Summary: This Package provide a callback daemon for a2billing
Name: %{name}
Version: %{version}
Release: %{release}
Source0: %{name}-%{unmangled_version}.tar.gz
License: GPL
Group: Development/Libraries
BuildRoot: %{_tmppath}/%{name}-%{version}-%{release}-buildroot
Prefix: %{_prefix}
BuildArch: noarch
Vendor: Belaid Arezqui <areski@gmail.com>
Url: http://www.asterisk2billing.org/

%description
UNKNOWN

%prep
%setup -n %{name}-%{unmangled_version} -n %{name}-%{unmangled_version}

%build
python setup.py build

%install
python setup.py install --single-version-externally-managed --root=$RPM_BUILD_ROOT --record=INSTALLED_FILES

%clean
rm -rf $RPM_BUILD_ROOT

%files -f INSTALLED_FILES
%defattr(-,root,root)
