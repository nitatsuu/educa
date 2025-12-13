function parseQuery(q) {
  q = (q || "").trim().toLowerCase();
  const parts = q.split(/\s+/).filter(Boolean);
  const tags = [];
  const text = [];

  for (const p of parts) {
    if (p.startsWith("#") && p.length > 1) tags.push(p.slice(1));
    else text.push(p);
  }
  return { tags, text: text.join(" ") };
}

function matchesItem(el, query) {
  const blob = (el.dataset.blob || "").toLowerCase();
  const tagsStr = (el.dataset.tags || "").toLowerCase();

  for (const t of query.tags) {
    if (!tagsStr.includes(t)) return false;
  }
  if (query.text && !blob.includes(query.text)) return false;
  return true;
}

function filterContainer(container, query, selector) {
  if (!container) return 0;
  const items = Array.from(container.querySelectorAll(selector));
  let visible = 0;

  for (const it of items) {
    const ok = matchesItem(it, query);
    it.style.display = ok ? "" : "none";
    if (ok) visible++;
  }
  return visible;
}

window.initTutorSearch = function initTutorSearch() {
  const input = document.getElementById("tutorSearch");
  const clear = document.getElementById("clearSearch");
  const tutorsGrid = document.getElementById("tutorsGrid");
  const coursesGrid = document.getElementById("coursesGrid");

  if (!input) return;

  function apply() {
    const q = parseQuery(input.value);

    const v1 = filterContainer(tutorsGrid, q, ".tutor-card");
    const v2 = filterContainer(coursesGrid, q, ".course-card");
    
    // если хочешь — можно потом скрывать заголовок "КУРСИ" когда 0
    // сейчас оставим как есть (для простоты)
  }

  input.addEventListener("input", apply);
  if (clear) clear.addEventListener("click", () => {
    input.value = "";
    apply();
    input.focus();
  });

  apply();
};
