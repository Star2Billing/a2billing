# This file is part of A2Billing (http://www.a2billing.net/)
#
# A2Billing, Commercial Open Source Telecom Billing platform,
# powered by Star2billing S.L. <http://www.star2billing.com/>
#
# @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
# @author      Belaid Arezqui <areski@gmail.com>
# @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
# @package     A2Billing
#
# Software License Agreement (GNU Affero General Public License)
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as
# published by the Free Software Foundation, either version 3 of the
# License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#
# Daemon to proceed Call-Back request from the a2billing plaftorm
#

from setuptools import setup, find_packages

setup(
    name="callback_daemon",
    version="1.1",
    packages=['callback_daemon'],
    package_data={
        '': ['*.txt', '*.conf', '*.debian', '*.rc']
    },
    entry_points={
        'console_scripts': [
            'a2b_callback_daemon=callback_daemon.a2b_callback_daemon:main'
        ]
    },
    author="Belaid Arezqui",
    author_email="areski@gmail.com",
    description="This Package provide a callback daemon for a2billing",
    license="AGPLv3+",
    keywords="callback a2billing daemon",
    url="http://www.asterisk2billing.org/",
    classifiers=[
        'Development Status :: 5 - Production/Stable',
        'Environment :: Console',
        'Intended Audience :: Developers, Users',
        'License :: OSI Approved :: GNU Affero General Public License v3 or later (AGPLv3+)',
        'Operating System :: OS Independent',
        'Programming Language :: Python',
        'Topic :: Software Development'
    ],
)
