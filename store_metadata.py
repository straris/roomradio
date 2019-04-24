import xml.etree.ElementTree as ET
import urllib2

tree = ET.parse(file=urllib2.urlopen('http://www.trion%i.com:6060/stat.xml'))
root = tree.getroot()
