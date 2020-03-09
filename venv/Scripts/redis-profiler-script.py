#!C:\Users\alexander.blaauwgeer\PycharmProjects\Firewalla4N6\venv\Scripts\python.exe
# EASY-INSTALL-ENTRY-SCRIPT: 'rdbtools==0.1.14','console_scripts','redis-profiler'
__requires__ = 'rdbtools==0.1.14'
import re
import sys
from pkg_resources import load_entry_point

if __name__ == '__main__':
    sys.argv[0] = re.sub(r'(-script\.pyw?|\.exe)?$', '', sys.argv[0])
    sys.exit(
        load_entry_point('rdbtools==0.1.14', 'console_scripts', 'redis-profiler')()
    )
