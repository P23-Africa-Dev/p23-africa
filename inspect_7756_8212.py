import json
import sys

sys.stdout.reconfigure(encoding='utf-8', errors='replace')

with open(r"C:\Users\USER\.gemini\antigravity-ide\brain\425649f1-241c-4260-9e0f-6be49c13fe82\scratch\figma_service_market_entry.json", "r", encoding="utf-8") as f:
    data = json.load(f)

def find_node(n, tid):
    if n.get("id") == tid:
        return [n]
    for c in n.get("children", []):
        res = find_node(c, tid)
        if res:
            return [n] + res
    return []

path = find_node(data["nodes"]["7733:8067"]["document"], "7756:8212")

for i, n in enumerate(path):
    bb = n.get('absoluteBoundingBox', {})
    fills = [f.get('type') for f in n.get('fills', []) if f.get('visible', True)]
    effects = n.get('effects', [])
    cr = n.get('cornerRadius')
    crs = n.get('rectangleCornerRadii')
    print(f"Level {i}: {n.get('id')} [{n.get('type')}] '{n.get('name')}' bbox={bb} layout={n.get('layoutMode')} gap={n.get('itemSpacing')} cr={cr} crs={crs} fills={fills} effects={effects}")

target = path[-1] if path else None

if target:
    print("\n--- Detailed Tree of Target Node ---")
    def dump_tree(n, d=0):
        ind = "  " * d
        bb = n.get('absoluteBoundingBox', {})
        pad = f"pad=(T:{n.get('paddingTop')}, R:{n.get('paddingRight')}, B:{n.get('paddingBottom')}, L:{n.get('paddingLeft')})" if 'paddingTop' in n else ""
        gap = f"gap={n.get('itemSpacing')}" if 'itemSpacing' in n else ""
        layout = f"layout={n.get('layoutMode')}" if 'layoutMode' in n else ""
        cr = f"cr={n.get('cornerRadius')} crs={n.get('rectangleCornerRadii')}"
        fills = n.get('fills', [])
        effects = n.get('effects', [])
        strokes = n.get('strokes', [])
        sw = n.get('strokeWeight')
        print(f"{ind}{n.get('id')} [{n.get('type')}] '{n.get('name')}' bbox={bb} {layout} {gap} {pad} {cr}")
        if n.get('type') == 'TEXT':
            print(f"{ind}   chars='{n.get('characters')}' style={n.get('style')}")
        if fills:
            print(f"{ind}   fills={fills}")
        if strokes:
            print(f"{ind}   strokes={strokes} sw={sw}")
        if effects:
            print(f"{ind}   effects={effects}")
        for c in n.get("children", []):
            dump_tree(c, d+1)

    dump_tree(target)
else:
    print("Node 7756:8212 not found directly, searching anywhere in document...")
    def search_id(n, tid):
        if n.get("id") == tid:
            return n
        for c in n.get("children", []):
            r = search_id(c, tid)
            if r:
                return r
        return None
    res = search_id(data["nodes"]["7733:8067"]["document"], "7756:8212")
    print("Found:", res)
