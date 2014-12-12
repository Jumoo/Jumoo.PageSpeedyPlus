#coding: UTF8
"""Html segmenter"""
from BeautifulSoup import BeautifulSoup as bsoup
from BeautifulSoup import BeautifulStoneSoup
import re

def normalize(text):
    """Normalize whitepace in C{text}.

    >>> normalize(u"   spam\\n\\tspam   SPAM")
    u'spam spam SPAM'
    """

    return u' '.join(text.split())

class Segmenter(object):
    """Html segmenter
    Retrieves the editable/translatable text from an HTML document.
    """

    def __init__(self):
        """Set up various regular expressions for splitting the text"""

        self.pre_parse_stripper = re.compile(u"|".join([u"<body*?>|</body>",
                                         u"<a[\s\S]*?>|</a>",
                                         u"<img[\s\S]*?>|</img>",
                                         u"<input[\s\S]*?>|</input>",
                                         u"<script*?>[\s\S]*?</script>",
                                         u"<form[\s\S]*?>|</form>",
										 u"&amp;",
										 u"&gt;",
										 u"with",
										 u"this"]),
                                         re.I | re.M)
        """Strip out unsightly tags before heading to the splitter"""

        self.splitter = re.compile(u'|'.join([u"<p*?>|</p>",
                                         u"<div*?>|</div>",
                                         u"<td*?>|</td>",
                                         u"<li*?>|</li>",
                                         u"<h\d*?>|</h\d>",
                                         u"<dd*?>|</dd>",
                                         u"<dt*?>|</dt>",
                                         u"<br*?>"]),
                                         re.I | re.M)
        """Split segments by certain tags (removing tags in bargain)
        These tags indicate a segment boundary"""

        self.charset_finder = re.compile(u'[\s\S]*<meta[\s\S]*?charset\s*=\s*([\S]+)"[\s\S]*?>[\s\S]*', re.I)
        """Find the charset if necessary"""

        self.soup = None

    def __str__(self):
        """So we can tell which segger we have (assuming multiple segmenter classes)"""
        return "HTML"

    def get_chunks(self, html_text):
        """Extract the text from the HTML file"""

        self.soup = bsoup(html_text, fromEncoding=self.getEncoding(html_text))

        # document title
        if self.soup.head:
            title = self.soup.head.title
            if title:
                yield title.string

        # image alt attributes, anchor title attributes, input value attributes
        for tag, attr in ((u"img", u"alt"),
                (u"a", u"title"),
                (u"input", u"value")):
            for chunk in self.getAttributes(tag, attr):
                if chunk:
                    yield chunk

        # Parse the body text
        if self.soup.body:
            text = self.pre_parse_stripper.sub(u"", unicode(self.soup.body))
            for chunk in self.splitter.split(text):
                normal = normalize(html2plain(chunk))
                if normal:
                    yield normal

    def getAttributes(self, tagName, attrName):
        """Get all attrName values for tagName tags"""

        attrs = []

        tags = self.soup.findAll(tagName)

        for tag in tags:
            try:
                attr = tag[attrName]
                if attr:
                    attrs.append(attr)
            except KeyError, e:
                #print "Tag %s does not have attribute %s" % (tagName, attrName)
                pass

        return attrs

    def getEncoding(self, text):
        """Retrieve the encoding META tag, if present"""

        m = self.charset_finder.match(text)
        if m:
            return m.groups(0)[0]
        return None

TAG_STRIPPER = re.compile(u"<[!\w/][\s\S]*?>", re.I | re.M)

def strip_tags(line):
    """strip the HTML tags from the line

    >>> strip_tags(u"<b>spam</b>")
    u'spam'

    """

    return TAG_STRIPPER.sub(u"", line)

def html2plain(text):
    """Strips out tags from HTML text

    >>> html2plain('spam <b>eggs</b>')
    u'spam\\xa0eggs'
    >>> html2plain('–>')
    u'–>'
    """

    entities = BeautifulStoneSoup.HTML_ENTITIES
    text = unicode(BeautifulStoneSoup(strip_tags(text),
                                      convertEntities=entities))
    return text.replace(u"&#38;gt;", ">").replace(u"&#38;lt;", "<")