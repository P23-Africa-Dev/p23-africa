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


def fetch(path, accept_language="en"):
    req = urllib.request.Request(
        BASE + path,
        headers={"User-Agent": UA, "Accept": "text/html,application/xml", "Accept-Language": accept_language},
    )
    try:
        res = opener.open(req, timeout=30)
        raw = res.read()
        return res.status, res.headers, raw, ""
    except Exception as e:
        status = getattr(e, "code", 0) or 0
        headers = getattr(e, "headers", None)
        raw = b""
        if hasattr(e, "read"):
            try:
                raw = e.read()
            except Exception:
                pass
        loc = headers.get("Location") if headers else ""
        return status, headers, raw, loc or ""


def text_of(raw):
    html = raw.decode("utf-8", "replace")
    visible = re.sub(r"<script[\s\S]*?</script>|<style[\s\S]*?</style>", " ", html, flags=re.I)
    visible = re.sub(r"<[^>]+>", " ", visible)
    visible = re.sub(r"\s+", " ", visible).strip()
    return html, visible


checks = []


def expect(path, status, needle, accept_language="en", header=None, absent=None):
    code, headers, raw, loc = fetch(path, accept_language)
    html, visible = text_of(raw)
    ok = code == status and (needle in html or needle in visible)
    if absent and absent in html:
        ok = False
    header_note = ""
    if header and headers is not None:
        got = headers.get(header[0], "")
        header_note = f" {header[0]}={got}"
        if header[1] not in (got or ""):
            ok = False
    print(f"{'OK' if ok else 'FAIL'} {code} {path} loc={loc} chars={len(visible)}{header_note}")
    if not ok:
        print("  needle:", needle[:80])
        print("  sample:", visible[:180])
    checks.append(ok)


expect("/", 200, "P23")
expect("/about", 200, "privacy")
expect("/services", 200, "privacy")
expect("/contact", 200, "contact@")
expect("/privacy-policy", 200, "do not sell")
expect("/terms-and-conditions", 200, "not legal")
expect("/fr/privacy-policy", 200, "Politique de confidentialité")
expect("/de/terms-and-conditions", 200, "Nutzungsbedingungen")
expect("/nl/privacy-policy", 200, "Privacybeleid")
expect("/quiz/start", 200, "How do you track potential leads")
expect("/quiz/start", 200, "structured for growth")
expect("/fr/quiz/start", 200, "Comment suivez-vous les prospects")
expect("/quiz/result/fragile-funnel", 200, "FRAGILE FUNNEL")
expect("/quiz/result/hidden-powerhouse", 200, "Hidden Powerhouse")
expect("/quiz/result/cloudy-climber", 200, "Cloudy Climber")
expect("/case-studies", 200, "case")
expect("/sitemap.xml", 200, "<urlset")
expect("/sitemap.xml", 200, "/privacy-policy")
expect("/sitemap.xml", 200, "/case-studies/")
expect("/sales", 200, "noindex", header=("X-Robots-Tag", "noindex"))
expect("/contact", 302, "", accept_language="fr")
expect("/admin/login", 200, "password")

code, headers, raw, loc = fetch("/sitemap.xml")
html = raw.decode("utf-8", "replace")
sales_locs = re.findall(r"<loc>[^<]*/sales</loc>", html)
dead = "building-smart-sales-systems-for-african-businesses" in html
print(f"{'OK' if not sales_locs else 'FAIL'} sitemap sales locs={len(sales_locs)}")
print(f"{'OK' if not dead else 'FAIL'} sitemap omits dead event slug={dead}")
checks.append(not sales_locs)
checks.append(not dead)

code, headers, raw, loc = fetch("/contact", "fr")
print(f"{'OK' if loc.endswith('/fr/contact') else 'FAIL'} french contact redirect -> {loc}")
checks.append(loc.endswith("/fr/contact"))

print("PASSED" if all(checks) else f"FAILED {sum(1 for c in checks if not c)} of {len(checks)}")
sys.exit(0 if all(checks) else 1)
