import urllib2
import re
import subprocess
import urllib

searchTerm = r'<td class="streamstats">(.*?)<\/td>'
pattern = re.compile(searchTerm, re.UNICODE)


def getMetadata():
  response = urllib2.urlopen('http://localhost:8000/status.xsl')
  html = response.read()
  metadataList = pattern.findall(html)
  title = metadataList[5]
  f = open("/var/www/roomradio/songtitle.txt","w")
  f.write(title)
  f.close()

def getImageUrl():
  proc = subprocess.check_output(["php", "/var/www/roomradio/metadata.php"], stderr=subprocess.STDOUT)
  return proc

def saveImage():
  urllib.urlretrieve(getImageUrl(), "/var/www/roomradio/image.jpg")

getMetadata()
saveImage()

