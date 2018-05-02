"""Hacky constants file used by other python scripts in this folder"""

import datetime

from pyiem.network import Table as NetworkTable
from pyiem.util import get_dbconn

nt = NetworkTable(('IACLIMATE', 'ILCLIMATE', 'INCLIMATE', 'OHCLIMATE',
                   'MICLIMATE', 'KYCLIMATE', 'WICLIMATE', 'MNCLIMATE',
                   'SDCLIMATE', 'NDCLIMATE', 'NECLIMATE', 'KSCLIMATE',
                   'MOCLIMATE'))

_THISYEAR = datetime.datetime.now().year
_ENDYEAR = datetime.datetime.now().year + 1

_ARCHIVEENDTS = datetime.datetime.now() - datetime.timedelta(days=1)
_ENDTS = datetime.datetime(_ENDYEAR, 1, 1)

mesosite = get_dbconn('mesosite', user='nobody')
mcursor = mesosite.cursor()
mcursor.execute("""
    SELECT propvalue from properties where propname = 'iaclimate.end'
""")
row = mcursor.fetchone()
_QCENDTS = datetime.datetime.strptime(row[0], '%Y-%m-%d')
mcursor.close()


def get_table(sid):
    """
    Return the table which has the data for this siteID
    """
    return "alldata_%s" % (sid[:2],)


def yrcnt(sid):
    """ Compute the number of years each month will have in the records """
    sts = startts(sid)
    r = [0]*13
    for m in range(1, 13):
        ts = datetime.datetime.now().replace(month=m, day=1)
        if ts > _ARCHIVEENDTS:
            r[m] = _ARCHIVEENDTS.year - sts.year
        else:
            r[m] = _ARCHIVEENDTS.year - sts.year + 1

    return r


def climatetable(sid):
    if startyear(sid) == 1951:
        return "climate51"
    return "climate"


def startts(sid):
    return datetime.datetime(startyear(sid), 1, 1)


def startyear(sid):
    """ Return the start year for this station ID """
    if nt.sts[sid]['archive_begin'].year <= 1893:
        return 1893
    return 1951


def make_output(nt, station, reportid):
    """ Create and return the output file used for this reportid """
    fn = "/mesonet/share/climodat/reports/%s_%s.txt" % (station, reportid)
    fp = open(fn, 'w')
    fp.write("""# IEM Climodat https://mesonet.agron.iastate.edu/climodat/
# Report Generated: %s
# Climate Record: %s -> %s (data after %s is preliminary)
# Site Information: [%s] %s
# Contact Information: Daryl Herzmann akrherz@iastate.edu 515.294.5978
""" % (datetime.datetime.now().strftime("%d %b %Y"),
       startts(station).strftime("%d %b %Y"),
       _ARCHIVEENDTS.strftime("%d %b %Y"), _QCENDTS.strftime("%d %b %Y"),
       station, nt.sts[station]["name"]))
    return fp
