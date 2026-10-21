import json
import sys

sys.stdout.reconfigure(encoding='utf-8', errors='replace')

with open(r"C:\Users\USER\.gemini\antigravity-ide\brain\425649f1-241c-4260-9e0f-6be49c13fe82\scratch\figma_service_market_entry.json", "r", encoding="utf-8") as f:
    data = json.load(f)

def find_node(n, tid):
    if n.get("id") == tid:
        return n
    for c in n.get("children", []):
        r = find_node(c, tid)
        if r:
            return r
    return None

g = find_node(data["nodes"]["7733:8067"]["document"], "7756:8211")

def dump(n, d=0):
    ind = "  " * d
    bb = n.get('absoluteBoundingBox', {})
    cr = n.get('cornerRadius')
    crs = n.get('rectangleCornerRadii')
    print(f"{ind}{n.get('id')} [{n.get('type')}] '{n.get('name')}' bbox={bb} cr={cr} crs={crs}")
    if n.get('type') == 'TEXT':
        print(f"{ind}   text='{n.get('characters')}' style={n.get('style')}")
    if n.get('fills'):
        print(f"{ind}   fills={n.get('fills')}")
    if n.get('strokes'):
        print(f"{ind}   strokes={n.get('strokes')} sw={n.get('strokeWeight')}")
    if n.get('effects'):
        print(f"{ind}   effects={n.get('effects')}")
    for c in n.get("children", []):
        dump(c, d+1)

dump(g)
