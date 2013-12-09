from setuptools import setup, find_packages

setup(
    name = "callback_daemon",
    version = "1.0",
    packages = ['callback_daemon'],

    package_data = {
        '': ['*.txt', '*.conf', '*.debian', '*.rc']
    },

    entry_points = {
        'console_scripts': [
            'a2b_callback_daemon = callback_daemon.a2b_callback_daemon:main'
        ]
    },

    # metadata
    author = "Belaid Arezqui",
    author_email = "areski@gmail.com",
    description = "This Package provide a callback daemon for a2billing",
    license = "GPL",
    keywords = "callback a2billing daemon",
    url = "http://www.asterisk2billing.org/"
)
