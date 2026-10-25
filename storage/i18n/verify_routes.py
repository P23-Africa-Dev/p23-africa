import re
import sys
import urllib.request

sys.stdout.reconfigure(encoding="utf-8", errors="replace")

BASE = "http://127.0.0.1:8000"
UA = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36"


class NoRedir(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None


opener = urllib.request.build_opener(NoRedir)


def fetch(path):
    req = urllib.request.Request(
        BASE + path,
        headers={"User-Agent": UA, "Accept": "text/html,application/xml", "Accept-Language": "en"},
    )
    try:
        res = opener.open(req, timeout=30)
        raw = res.read()
        return res.status, "", raw
    except Exception as e:
        status = getattr(e, "code", 0) or 0
        headers = getattr(e, "headers", None)
        loc = headers.get("Location") if headers else ""
        raw = b""
        if hasattr(e, "read"):
            try:
                raw = e.read(400)
            except Exception:
                pass
        return status, loc or "", raw


xml = urllib.request.urlopen(BASE + "/sitemap.xml", timeout=30).read().decode("utf-8", "replace")
locs = re.findall(r"<loc>(.*?)</loc>", xml)
paths = []
for loc in locs:
    path = loc.split("127.0.0.1:8000", 1)[-1]
    if path.startswith("/fr/") or path.startswith("/de/") or path.startswith("/nl/") or path in ("/fr", "/de", "/nl"):
        continue
    paths.append(path if path else "/")

extra = ["/fr", "/de", "/nl", "/fr/about", "/de/services", "/nl/contact", "/sales", "/book-consultation/form"]
seen = []
bad = []
for path in paths + extra:
    if path in seen:
        continue
    seen.append(path)
    status, loc, raw = fetch(path)
    html = raw.decode("utf-8", "replace")
    problem = status >= 400 or status == 0 or "Server Error" in html
    if problem:
        bad.append((status, path, loc, html[:120].replace("\n", " ")))
        print(f"FAIL {status} {path} loc={loc}")
    else:
        print(f"OK {status} {path}{(' -> ' + loc) if loc else ''}")

print(f"checked={len(seen)} failed={len(bad)}")
sys.exit(1 if bad else 0)
