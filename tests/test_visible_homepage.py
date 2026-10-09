"""Prevent shipping an unchanged root homepage or dead navigation."""
from html.parser import HTMLParser
from pathlib import Path
import re

ROOT = Path(__file__).resolve().parents[1]

class Home(HTMLParser):
    def __init__(self):
        super().__init__()
        self.ids = set()
        self.links = []
        self.images = []
        self.buttons = 0
    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if a.get("id"):
            self.ids.add(a["id"])
        if tag == "a":
            self.links.append(a.get("href",""))
        if tag == "img":
            self.images.append(a.get("src",""))
        if tag == "button":
            self.buttons += 1

html = (ROOT / "index.html").read_text(encoding="utf-8")
legacy = (ROOT / "Home/Home_page.html").read_text(encoding="utf-8")
page = Home()
page.feed(html)
expected = ("accueil","institution","formations","admissions","campus","actualites","portail","demonstration","faq","contact")
for name in expected:
    assert name in page.ids, f"Missing homepage section {name}"
for anchor in page.links:
    if anchor.startswith("#"):
        assert anchor[1:] in page.ids, f"Broken link {anchor}"
assert page.buttons >= 7, "Buttons not present"
assert len(page.images) >= 5, "Expected authentic project photos"
assert all(src.startswith("public/assets/photos/") for src in page.images)
assert 'data-filter="bts"' in html and 'data-filter="master"' in html
assert 'data-role="teacher"' in html and 'data-role="student"' in html
assert 'data-open-role="teacher"' in html
assert 'public/assets/photos/campus.jpg' in html
assert 'url=../index.html' in legacy, "Legacy homepage not redirected"
assert "<marquee" not in html.lower()
assert not re.search(r'href="#["\s]',html)
for src in set(page.images):
    assert src.rsplit("/",1)[-1] in {"campus.jpg","atelier.jpg","atelier-sante.jpg","ingenierie.jpg","salle.jpg"}
print("PASS: homepage, navigation, photos, interactive options")
