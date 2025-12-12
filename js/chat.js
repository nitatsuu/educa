let chatTimer = null;

function esc(s) {
  return (s ?? "").toString()
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;");
}

async function fetchMessages(withUser) {
  const r = await fetch(`/php/get_messages.php?with=${encodeURIComponent(withUser)}`, { cache: "no-store" });
  if (!r.ok) return null;
  return await r.json();
}

function renderMessages(data) {
  const box = document.getElementById("chatBox");
  if (!box) return;

  box.innerHTML = "";

  for (const m of (data.messages || [])) {
    const mine = m.from === data.me;
    const row = document.createElement("div");
    row.className = "msg-row " + (mine ? "msg-row--mine" : "msg-row--their");

    const bubble = document.createElement("div");
    bubble.className = "msg-bubble";
    bubble.innerHTML = `
      <div class="msg-text">${esc(m.text)}</div>
      <div class="msg-time">${esc(new Date(m.ts).toLocaleString())}</div>
    `;

    row.appendChild(bubble);
    box.appendChild(row);
  }

  // автоскролл вниз
  box.scrollTop = box.scrollHeight;
}

async function sendMessage(withUser, text) {
  const fd = new FormData();
  fd.append("with", withUser);
  fd.append("text", text);

  const r = await fetch("/php/send_message.php", { method: "POST", body: fd });
  return r.ok;
}

window.initChat = function initChat() {
  const withUser = document.body.dataset.chatWith;
  const form = document.getElementById("chatForm");
  const input = document.getElementById("chatInput");

  if (!withUser || !form || !input) return;

  async function tick() {
    const data = await fetchMessages(withUser);
    if (data) renderMessages(data);
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const text = input.value.trim();
    if (!text) return;

    input.value = "";
    await sendMessage(withUser, text);
    await tick();
    input.focus();
  });

  tick();
  chatTimer = setInterval(tick, 1200);
};
