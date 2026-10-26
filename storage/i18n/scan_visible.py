import re
from pathlib import Path

root = Path(r"c:\xampp\htdocs\P23 Africa\resources\views")
files = [
    "brn-form.blade.php",
    "brn-form2.blade.php",
    "consultation/index.blade.php",
    "consultation/application-forms.blade.php",
    "consultation/schedule.blade.php",
    "consultation/schedule-consultation.blade.php",
    "consultation/payment-selection.blade.php",
    "consultation/payment-success.blade.php",
    "consultation/consultation-summary.blade.php",
    "playbook-landing.blade.php",
    "playbook/checkout.blade.php",
    "playbook/funding-readiness.blade.php",
    "playbook/ultimate-funding.blade.php",
    "playbook/payment.blade.php",
    "sales.blade.php",
    "sales-quiz.blade.php",
    "start-quiz.blade.php",
    "include/quiz-questions-js.blade.php",
    "include/book-seat.blade.php",
    "feedback/form.blade.php",
    "show-event.blade.php",
    "events/live.blade.php",
    "quiz-answers/result-a.blade.php",
    "quiz-answers/result-b.blade.php",
    "quiz-answers/result-c.blade.php",
]

comment = re.compile(r"\{\{--.*?--\}\}", re.S)
html_comment = re.compile(r"<!--.*?-->", re.S)
style = re.compile(r"<style\b.*?</style>", re.S | re.I)
tag_text = re.compile(r">([^<]{3,})")
attr = re.compile(r'''(?:placeholder|aria-label|title|alt)\s*=\s*"([^"]{3,})"''')

out = Path(r"c:\xampp\htdocs\P23 Africa\storage\i18n\visible.txt")
lines = []
for rel in files:
    raw = (root / rel).read_text(encoding="utf-8")
    raw = comment.sub("", raw)
    raw = html_comment.sub("", raw)
    raw = style.sub("", raw)
    found = []
    for m in tag_text.finditer(raw):
        t = " ".join(m.group(1).split())
        if not t or t.startswith("@") or t.startswith("{{") or "{{" in t:
            continue
        if not re.search(r"[A-Za-z]", t):
            continue
        if len(t) < 4:
            continue
        found.append(t)
    for m in attr.finditer(raw):
        t = " ".join(m.group(1).split())
        if "{{" in t or not re.search(r"[A-Za-z]", t):
            continue
        found.append("ATTR " + t)
    uniq = []
    seen = set()
    for t in found:
        if t not in seen:
            seen.add(t)
            uniq.append(t)
    lines.append(f"\n===== {rel} ({len(uniq)}) =====")
    lines.extend(uniq)

out.write_text("\n".join(lines), encoding="utf-8")
print("wrote", out, "chars", out.stat().st_size)
