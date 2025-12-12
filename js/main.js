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

function matchesTutor(card, query) {
  const blob = (card.dataset.blob || "").toLowerCase();
  const tagsStr = (card.dataset.tags || "").toLowerCase();

  // все теги должны присутствовать
  for (const t of query.tags) {
    if (!tagsStr.includes(t)) return false;
  }

  // если есть текст — ищем в blob
  if (query.text && !blob.includes(query.text)) return false;

  return true;
}

window.initTutorSearch = function initTutorSearch() {
  const input = document.getElementById("tutorSearch");
  const clear = document.getElementById("clearSearch");
  const grid = document.getElementById("tutorsGrid");
  if (!input || !grid) return;

  const cards = Array.from(grid.querySelectorAll(".tutor-card"));

  function apply() {
    const q = parseQuery(input.value);
    let visible = 0;

    for (const c of cards) {
      const ok = matchesTutor(c, q);
      c.style.display = ok ? "" : "none";
      if (ok) visible++;
    }
  }

  input.addEventListener("input", apply);
  if (clear) clear.addEventListener("click", () => {
    input.value = "";
    apply();
    input.focus();
  });

  apply();
};
