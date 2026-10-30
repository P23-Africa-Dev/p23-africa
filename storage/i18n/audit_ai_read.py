# -*- coding: utf-8 -*-
"""See which public production URLs an AI fetcher would actually receive."""
import re
import urllib.request
from html.parser import HTMLParser
from urllib.parse import urljoin, urlparse

BASE = "https://p23africa.com"
UA = "Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko); compatible; ClaudeBot/1.0; +https://www.anthropic.com/claude-bot"
SEED = [
    "/", "/about", "/services", "/contact", "/events", "/all-events", "/resource-hub",
    "/case-studies", "/case-studies/thermoflow-hvac", "/market-entry", "/market-entry/nigeria",
    "/market-entry/ghana", "/distributor-partner-development", "/outbound-sales-demand-testing",
    "/noel54", "/noel54/interest-form", "/student", "/sales", "/quiz", "/quiz/start",
    "/quiz/result/fragile-funnel", "/founders-playbook", "/playbook/funding-readiness",
    "/playbook/ultimate-funding", "/playbook/ultimate-funding/checkout",
    "/book-consultation", "/book-consultation/form", "/book-consultation/summary",
    "/book-consultation/payment-selection", "/book-consultation/schedule", "/book",
    "/res/archive", "/res/archive/list", "/feedback", "/admin/login", "/sitemap.xml",
    "/fr", "/fr/about", "/de/services", "/nl/contact",
    "/events/building-smart-sales-systems-for-african-businesses",
    "/events/the-54th-pitch-competition",
    "/services/market-entry",
]


class LinkParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.links = []
        self._skip = 0
        self._text = []
        self.title = ""
        self._in_title = False
        self.robots = ""

    def handle_starttag(self, tag, attrs):
        ad = dict(attrs)
        if tag in ("script", "style", "noscript"):
            self._skip += 1
        if tag == "title":
            self._in_title = True
        if tag == "a" and ad.get("href"):
            self.links.append(ad["href"])
        if tag == "meta" and ad.get("name", "").lower() == "robots":
            self.robots = ad.get("content", "")

    def handle_endtag(self, tag):
        if tag in ("script", "style", "noscript") and self._skip:
            self._skip -= 1
        if tag == "title":
            self._in_title = False

    def handle_data(self, data):
        if self._in_title:
            self.title += data
        if self._skip:
            return
        self._text.append(data)

    def text(self):
        return re.sub(r"\s+", " ", " ".join(self._text)).strip()


def fetch(path):
    url = path if path.startswith("http") else BASE + path
    req = urllib.request.Request(url, headers={"User-Agent": UA, "Accept": "text/html,application/xhtml+xml"})
    try:
        with urllib.request.urlopen(req, timeout=25) as res:
            raw = res.read(1_500_000)
            return res.status, res.geturl(), res.headers, raw
    except Exception as e:
        code = getattr(e, "code", 0) or 0
        hdrs = getattr(e, "headers", {})
        loc = hdrs.get("Location") if hdrs else ""
        body = b""
        if hasattr(e, "read"):
            try:
                body = e.read(20000)
            except Exception:
                body = b""
        return code, loc or url, hdrs, body


def main():
    seen = []
    queue = list(SEED)
    discovered = []
    for path in list(queue):
        status, final, hdrs, raw = fetch(path)
        ctype = (hdrs.get("Content-Type") if hdrs else "") or ""
        robots_hdr = (hdrs.get("X-Robots-Tag") if hdrs else "") or ""
        html = raw.decode("utf-8", "replace")
        parser = LinkParser()
        visible = ""
        title = ""
        try:
            parser.feed(html)
            visible = parser.text()
            title = re.sub(r"\s+", " ", parser.title).strip()
        except Exception as exc:
            visible = f"PARSE_ERROR {exc}"
        if path in ("/", "/services", "/resource-hub", "/events", "/all-events", "/about"):
            for href in parser.links:
                absu = urljoin(BASE + "/", href)
                parsed = urlparse(absu)
                if parsed.netloc.endswith("p23africa.com") and parsed.path not in queue and parsed.path not in discovered:
                    if not any(parsed.path.startswith(p) for p in ("/admin", "/storage", "/build")):
                        discovered.append(parsed.path)
        flag = []
        if status >= 400 or status == 0:
            flag.append(f"http-{status or 'fail'}")
        if status in (301, 302, 303, 307, 308):
            flag.append("redirect")
        if "noindex" in (parser.robots + " " + robots_hdr).lower():
            flag.append("noindex")
        if len(visible) < 400 and status == 200 and "html" in ctype:
            flag.append("thin-html")
        if "Just a moment" in html or "cf-browser-verification" in html or "Attention Required" in html:
            flag.append("bot-challenge")
        if "Server Error" in visible or "500" in title:
            flag.append("error-page")
        if re.search(r"loading\.\.\.|no events found", visible, re.I) and len(visible) < 800:
            flag.append("js-shell")
        print(f"{status:3} text={len(visible):5} {path} -> {final.replace(BASE,'')} | {title[:70]} | {','.join(flag)} | {visible[:110]}")
        seen.append(path)
    print("--- DISCOVERED ---")
    for path in discovered:
        if path in seen or path in SEED:
            continue
        status, final, hdrs, raw = fetch(path)
        html = raw.decode("utf-8", "replace")
        parser = LinkParser()
        try:
            parser.feed(html)
            visible = parser.text()
            title = re.sub(r"\s+", " ", parser.title).strip()
        except Exception:
            visible, title = "", ""
        ctype = (hdrs.get("Content-Type") if hdrs else "") or ""
        flag = []
        if status >= 400 or status == 0:
            flag.append(f"http-{status or 'fail'}")
        if len(visible) < 400 and status == 200 and "html" in ctype:
            flag.append("thin-html")
        if status in (301, 302):
            flag.append("redirect")
        print(f"{status:3} text={len(visible):5} {path} -> {final.replace(BASE,'')} | {title[:70]} | {','.join(flag)} | {visible[:110]}")


if __name__ == "__main__":
    main()
