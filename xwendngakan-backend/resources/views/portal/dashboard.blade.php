@extends('portal.layout')
@section('title', 'داشبۆرد — EduBook')
@section('styles')
<style>
/* ════════════════════════════════════════════════
   TOKENS – Premium Dark Gold
════════════════════════════════════════════════ */
:root {
  --gold:    #e2b042;
  --gold-lt: #fbbf24;
  --gold-dk: #b88728;
  --bg:      #060a12;
  --bg2:     #0c1220;
  --bg3:     #151e30;
  --bg4:     #1e2d46;
  --border:  rgba(226, 176, 66, 0.10);
  --border2: rgba(226, 176, 66, 0.24);
  --txt:     #f1f5f9;
  --txt2:    #8b9ec2;
  --txt3:    #556680;
  --red:     #ef4444;
  --green:   #10b981;
  --grad:    linear-gradient(135deg, #b88728, #fbbf24);
  --grad2:   linear-gradient(135deg, #1a1040, #0c1220, #0a1628);
  --glass:   rgba(12, 18, 32, 0.65);
  --glass2:  rgba(20, 30, 50, 0.45);
  --radius:  18px;
  --radius-sm: 12px;
  --ease:    cubic-bezier(0.4, 0, 0.2, 1);
}

/* ════════════════════════════════════════════════
   LAYOUT
════════════════════════════════════════════════ */
*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }

.db {
  display: flex;
  min-height: calc(100vh - 66px);
  background: var(--bg);
  direction: rtl;
  position: relative;
  overflow: hidden;
}
.db::before, .db::after {
  content: '';
  position: absolute;
  border-radius: 50%;
  filter: blur(120px);
  z-index: 0;
  pointer-events: none;
  animation: floatOrb 15s ease-in-out infinite alternate;
}
.db::before {
  width: 600px; height: 600px;
  background: rgba(226, 176, 66, 0.08);
  top: -200px; left: -100px;
}
.db::after {
  width: 500px; height: 500px;
  background: rgba(16, 185, 129, 0.03);
  bottom: -150px; right: -100px;
  animation-delay: -5s;
}
@keyframes floatOrb {
  0% { transform: translate(0, 0) scale(1); }
  100% { transform: translate(100px, 50px) scale(1.15); }
}
.db-side, .db-main { position: relative; z-index: 1; }

/* ════════════════════════════════════════════════
   SIDEBAR
════════════════════════════════════════════════ */
.db-side {
  width: 240px;
  flex-shrink: 0;
  background: var(--bg2);
  border-left: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  position: sticky;
  top: 66px;
  height: calc(100vh - 66px);
  overflow-y: auto;
  padding: 1.25rem .85rem 1rem;
}

.db-avatar {
  display: flex;
  align-items: center;
  gap: .75rem;
  padding: .75rem .5rem 1.25rem;
  border-bottom: 1px solid var(--border);
  margin-bottom: 1.1rem;
}
.db-avatar-circle {
  width: 42px; height: 42px;
  border-radius: 12px;
  background: var(--grad);
  box-shadow: 0 4px 18px rgba(226, 176, 66, 0.30);
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; font-weight: 900; color: #080c14;
  flex-shrink: 0;
  position: relative;
}
.db-avatar-circle::after {
  content: '';
  position: absolute; inset: -2px;
  border-radius: 14px;
  border: 1.5px solid rgba(226,176,66,.25);
  pointer-events: none;
}
.db-avatar-name  { font-size: .87rem; font-weight: 700; color: var(--txt); line-height: 1.3; }
.db-avatar-email { font-size: .71rem; color: var(--txt3); margin-top: 1px; word-break: break-all; }

.db-nav { display: flex; flex-direction: column; gap: 4px; }
.db-nav-btn {
  display: flex; align-items: center; gap: 10px;
  padding: 11px 14px;
  border-radius: 12px; border: none;
  background: transparent;
  color: var(--txt2);
  font-family: inherit; font-size: .87rem; font-weight: 700;
  cursor: pointer; width: 100%; text-align: right;
  transition: all .2s var(--ease);
  position: relative;
}
.db-nav-btn:hover { background: rgba(226, 176, 66, 0.07); color: var(--gold-lt); }
.db-nav-btn.is-active {
  background: rgba(226, 176, 66, 0.10);
  color: var(--gold-lt);
  box-shadow: inset 0 0 0 1px rgba(226,176,66,.12);
}
.db-nav-btn.is-active::before {
  content: '';
  position: absolute; right: 0; top: 18%; height: 64%; width: 3px;
  background: var(--grad);
  border-radius: 2px 0 0 2px;
  box-shadow: 0 0 8px rgba(226,176,66,.35);
}
.db-nav-icon { font-size: 1.05rem; flex-shrink: 0; }
.db-nav-badge {
  margin-right: auto;
  background: rgba(226, 176, 66, 0.16);
  color: var(--gold-lt);
  font-size: .65rem; padding: 2px 8px;
  border-radius: 20px; font-weight: 800;
}
.db-sep { height: 1px; background: var(--border); margin: .75rem 0; }

.db-logout-btn {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 14px; border-radius: 12px; border: none;
  background: transparent; color: var(--txt3);
  font-family: inherit; font-size: .84rem; font-weight: 700;
  cursor: pointer; width: 100%; margin-top: auto;
  transition: all .2s var(--ease);
}
.db-logout-btn:hover { background: rgba(255,69,69,.08); color: #ff8080; }

/* ════════════════════════════════════════════════
   MAIN
════════════════════════════════════════════════ */
.db-main {
  flex: 1;
  padding: 2.5rem 3rem 5rem;
  max-width: 980px;
  overflow-y: auto;
}

.db-tab { display: none; animation: dbFade .35s var(--ease); }
.db-tab.is-active { display: block; }
@keyframes dbFade { from { opacity:0; transform:translateY(12px) } to { opacity:1; transform:none } }

/* ── Page header ── */
.pg-head { margin-bottom: 2.25rem; position: relative; }
.pg-title {
  font-size: 2.2rem; font-weight: 900;
  color: var(--txt); letter-spacing: -.03em;
  line-height: 1.2;
}
.pg-title span {
  background: linear-gradient(to right, var(--gold-lt), var(--gold), #ff9d00, var(--gold-lt));
  background-size: 200% auto;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: shineText 4s linear infinite;
}
@keyframes shineText {
  to { background-position: 200% center; }
}
.pg-sub {
  font-size: .86rem; color: var(--txt2); margin-top: .5rem;
  opacity: .85;
}

.pg-head-row {
  display: flex; align-items: flex-end;
  justify-content: space-between;
  flex-wrap: wrap; gap: 1rem;
  margin-bottom: 1.75rem;
}

/* ── Notice ── */
.nt {
  display: flex; align-items: flex-start; gap: 14px;
  padding: 1.1rem 1.35rem; border-radius: 14px;
  margin-bottom: 1.5rem; font-size: .86rem; font-weight: 600;
  line-height: 1.55;
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}
.nt-warn {
  background: rgba(251,191,36,.05);
  border: 1px solid rgba(251,191,36,.18);
  color: #fbbf24;
  box-shadow: 0 0 20px rgba(251,191,36,.04);
}
.nt-ok {
  background: rgba(34,197,94,.05);
  border: 1px solid rgba(34,197,94,.18);
  color: #4ade80;
  box-shadow: 0 0 20px rgba(34,197,94,.04);
}
.nt-icon { font-size: 1.1rem; flex-shrink: 0; }
.nt-title { font-weight: 800; font-size: .88rem; }
.nt-sub   { font-size: .78rem; opacity: .7; margin-top: 2px; }

/* ════════════════════════════════════════════════
   CARDS – Glassmorphism
════════════════════════════════════════════════ */
.db-card {
  background: rgba(12, 18, 32, 0.45);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border: 1px solid rgba(255, 255, 255, 0.03);
  border-radius: var(--radius);
  padding: 2.15rem;
  margin-bottom: 1.5rem;
  position: relative;
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.05);
}
.db-card::before {
  content: ''; position: absolute; inset: 0;
  border-radius: var(--radius);
  padding: 1.5px;
  background: linear-gradient(135deg, rgba(226, 176, 66, 0.4), transparent 40%, rgba(226, 176, 66, 0.1));
  -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  pointer-events: none;
  opacity: 0.3; transition: opacity 0.4s var(--ease);
}
.db-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.1);
  border-color: rgba(226, 176, 66, 0.1);
}
.db-card:hover::before { opacity: 1; }

.db-card-head {
  display: flex; align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
  padding-bottom: 1.1rem;
  border-bottom: 1px solid rgba(226,176,66,.08);
  position: relative;
}
.db-card-head::after {
  content: '';
  position: absolute; bottom: -1px; right: 0;
  width: 60px; height: 2px;
  background: var(--grad);
  border-radius: 2px;
  opacity: .7;
}
.db-card-title {
  font-size: .82rem; font-weight: 800;
  letter-spacing: .08em; color: var(--txt2);
  text-transform: uppercase;
  display: flex; align-items: center; gap: 10px;
}
.db-card-title::before {
  content: '';
  width: 4px; height: 18px;
  background: var(--grad);
  border-radius: 3px; display: inline-block; flex-shrink: 0;
  box-shadow: 0 0 8px rgba(226,176,66,.3);
}

/* ════════════════════════════════════════════════
   FORM FIELDS – Modern Glass Style
════════════════════════════════════════════════ */
.f-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
.f-group { margin-bottom: 1rem; }

.f-label {
  display: flex; align-items: center; gap: 5px;
  font-size: .82rem; font-weight: 700;
  color: var(--txt2); margin-bottom: 8px;
  letter-spacing: .01em;
}
.f-req {
  color: #f87171;
  font-size: .7rem;
}

.f-input, .f-select, .f-textarea {
  width: 100%; padding: 14px 18px;
  background: rgba(6, 10, 18, 0.6);
  border: 1px solid rgba(255, 255, 255, 0.04);
  border-radius: var(--radius-sm);
  color: var(--txt);
  font-family: inherit; font-size: .93rem;
  outline: none; direction: rtl;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: inset 0 2px 5px rgba(0,0,0, 0.2);
}
.f-input:hover, .f-select:hover, .f-textarea:hover {
  background: rgba(10, 16, 28, 0.75);
  border-color: rgba(226, 176, 66, 0.25);
}
.f-input:focus, .f-select:focus, .f-textarea:focus {
  background: rgba(15, 22, 36, 0.85);
  border-color: var(--gold-lt);
  box-shadow: inset 0 2px 5px rgba(0,0,0, 0.2), 0 0 0 4px rgba(226, 176, 66, 0.15);
  transform: translateY(-2px);
}
.f-input::placeholder, .f-textarea::placeholder { color: var(--txt3); opacity: .6; }
.f-select { appearance: none; cursor: pointer; }
.f-select option { background: var(--bg2); color: var(--txt); }
.f-textarea { resize: vertical; min-height: 95px; line-height: 1.7; }

/* ── Translate btn ── */
.btn-tr {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 14px; border-radius: 9px;
  background: rgba(226,176,66,.08);
  color: var(--gold);
  border: 1px solid rgba(226,176,66,.18);
  font-family: inherit; font-size: .75rem; font-weight: 700;
  cursor: pointer;
  transition: all .2s var(--ease);
}
.btn-tr:hover {
  background: rgba(226,176,66,.15);
  border-color: rgba(226,176,66,.3);
  box-shadow: 0 0 12px rgba(226,176,66,.1);
}
.btn-tr.loading svg { animation: spin .9s linear infinite; }
@keyframes spin { to { transform: rotate(360deg) } }

/* ════════════════════════════════════════════════
   FILE UPLOAD – Modern Dashed
════════════════════════════════════════════════ */
.f-file {
  display: flex; flex-direction: column; align-items: center;
  padding: 1.75rem 1rem;
  border: 2px dashed rgba(226,176,66,.18);
  border-radius: 14px; cursor: pointer;
  background: rgba(226,176,66,.02);
  text-align: center;
  transition: all .3s var(--ease);
  position: relative;
  overflow: hidden;
}
.f-file::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(circle at center, rgba(226,176,66,.04), transparent 70%);
  opacity: 0;
  transition: opacity .3s;
}
.f-file:hover {
  border-color: rgba(226,176,66,.4);
  background: rgba(226,176,66,.06);
  transform: translateY(-1px);
  box-shadow: 0 4px 20px rgba(226,176,66,.06);
}
.f-file:hover::before { opacity: 1; }
.f-file input { display: none; }
.f-file-icon {
  font-size: 2rem; margin-bottom: .6rem;
  filter: drop-shadow(0 2px 8px rgba(226,176,66,.2));
}
.f-file-text { font-size: .84rem; color: var(--txt2); font-weight: 700; position: relative; }
.f-file-hint { font-size: .72rem; color: var(--txt3); margin-top: 5px; position: relative; }
.f-preview {
  width: 100%; max-height: 140px;
  object-fit: cover; border-radius: 10px;
  margin-top: .75rem; display: none;
  border: 1px solid rgba(226,176,66,.15);
  box-shadow: 0 4px 16px rgba(0,0,0,.3);
}

/* ── Dynamic item rows ── */
.item-row {
  display: flex; align-items: center; gap: .4rem; margin-bottom: .35rem;
}
.item-row .f-input { flex: 1; margin-bottom: 0; }
.rm-btn {
  flex-shrink: 0; width: 30px; height: 30px; border-radius: 8px;
  background: rgba(255,59,59,.08); color: #ff7070;
  border: 1px solid rgba(255,59,59,.15);
  font-size: 1.1rem; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s var(--ease); line-height: 1;
}
.rm-btn:hover { background: rgba(255,59,59,.18); transform: scale(1.05); }

.add-row-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 18px; border-radius: 10px;
  background: rgba(226,176,66,.05);
  color: var(--gold);
  border: 1px dashed rgba(226,176,66,.2);
  font-family: inherit; font-size: .79rem; font-weight: 700;
  cursor: pointer; margin-top: .5rem;
  transition: all .2s var(--ease);
}
.add-row-btn:hover {
  background: rgba(226,176,66,.12);
  border-color: rgba(226,176,66,.4);
}

/* ── Fee table ── */
.fee-header {
  display: grid; grid-template-columns: 2fr 1.3fr 1fr 30px;
  gap: .4rem; margin-bottom: .4rem; padding: 0 2px;
}
.fee-header span { font-size: .7rem; font-weight: 800; color: var(--txt3); }
.fee-row {
  display: grid; grid-template-columns: 2fr 1.3fr 1fr 30px;
  gap: .4rem; align-items: center; margin-bottom: .35rem;
}
.fee-row .f-input { margin-bottom: 0; }

/* ════════════════════════════════════════════════
   COLLEGE CARDS – Nested Glass
════════════════════════════════════════════════ */
.college-wrap { display: flex; flex-direction: column; gap: .85rem; }

.college-card {
  border: 1px solid rgba(226,176,66,.15);
  border-radius: 16px;
  overflow: hidden;
  background: linear-gradient(165deg, rgba(226,176,66,.03) 0%, rgba(12,18,32,.8) 100%);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  transition: all .3s var(--ease);
}
.college-card:hover {
  border-color: rgba(226,176,66,.3);
  box-shadow: 0 8px 32px rgba(0,0,0,.25), 0 0 0 1px rgba(226,176,66,.05);
  transform: translateY(-1px);
}

.college-header {
  display: flex; align-items: center; gap: .7rem;
  background: rgba(226,176,66,.04);
  border-bottom: 1px solid rgba(226,176,66,.10);
  padding: .75rem 1.1rem;
}
.college-badge {
  font-size: .65rem; font-weight: 900;
  letter-spacing: .12em; text-transform: uppercase;
  color: var(--gold); white-space: nowrap; flex-shrink: 0;
  display: flex; align-items: center; gap: 6px;
}
.college-badge::before {
  content: ''; width: 7px; height: 7px;
  background: var(--gold); border-radius: 50%;
  box-shadow: 0 0 10px rgba(226,176,66,.6); flex-shrink: 0;
  animation: collegePulse 2.5s ease-in-out infinite;
}
@keyframes collegePulse {
  0%, 100% { box-shadow: 0 0 6px rgba(226,176,66,.4); }
  50% { box-shadow: 0 0 14px rgba(226,176,66,.7); }
}
.college-header .clg-name {
  flex: 1; min-width: 0; margin-bottom: 0;
  font-weight: 700; font-size: .92rem;
}
.college-del-btn {
  flex-shrink: 0; width: 32px; height: 32px; border-radius: 9px;
  background: rgba(255,70,70,.06); color: #ff7070;
  border: 1px solid rgba(255,70,70,.15); font-size: .85rem;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: all .2s var(--ease);
}
.college-del-btn:hover {
  background: rgba(255,70,70,.18);
  transform: scale(1.08);
  box-shadow: 0 0 12px rgba(255,70,70,.1);
}

/* College fee strip */
.college-fee-strip {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: .5rem; padding: .65rem 1.1rem;
  border-bottom: 1px solid rgba(226,176,66,.07);
  background: rgba(0,0,0,.15);
}
.college-fee-field { display: flex; flex-direction: column; gap: 5px; }
.college-fee-label {
  font-size: .64rem; font-weight: 800;
  color: var(--txt3); letter-spacing: .05em;
  padding-right: 3px;
}
.college-fee-strip .f-input { margin-bottom: 0; font-size: .87rem; }

.college-body { padding: .9rem 1.1rem 1.1rem; }

.depts-header-row {
  display: flex; align-items: center; gap: 8px; margin-bottom: .55rem;
}
.depts-header-label {
  font-size: .66rem; font-weight: 900; letter-spacing: .1em;
  text-transform: uppercase; color: var(--txt3); white-space: nowrap;
}
.depts-header-line {
  flex: 1; height: 1px;
  background: linear-gradient(90deg, var(--border), transparent);
}

.dept-col-labels {
  display: grid; grid-template-columns: 1fr 120px 78px 30px;
  gap: .4rem; padding: 0 1px; margin-bottom: .35rem;
}
.dept-col-labels span {
  font-size: .64rem; font-weight: 800; color: var(--txt3); letter-spacing: .04em;
}

.dept-row {
  display: grid; grid-template-columns: 1fr 120px 78px 30px;
  gap: .4rem; align-items: center; margin-bottom: .4rem;
}
.dept-row .f-input {
  margin-bottom: 0; font-size: .86rem;
  background: rgba(6,10,18,.65); border-color: rgba(255,255,255,.05);
}
.dept-row .f-input:focus { background: var(--bg4); }

.dept-del-btn {
  width: 30px; height: 38px; border-radius: 9px;
  background: rgba(255,70,70,.05); color: #ff7070;
  border: 1px solid rgba(255,70,70,.12); font-size: .85rem;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: all .2s var(--ease); flex-shrink: 0;
}
.dept-del-btn:hover { background: rgba(255,70,70,.15); transform: scale(1.05); }

.add-dept-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 14px; border-radius: 9px;
  background: rgba(100,160,255,.04); color: #84b8ff;
  border: 1px dashed rgba(100,160,255,.18);
  font-family: inherit; font-size: .75rem; font-weight: 700;
  cursor: pointer; margin-top: .3rem;
  transition: all .2s var(--ease);
}
.add-dept-btn:hover {
  background: rgba(100,160,255,.1);
  border-color: rgba(100,160,255,.35);
  box-shadow: 0 0 12px rgba(100,160,255,.06);
}

.add-college-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; padding: 14px;
  border-radius: 14px;
  background: rgba(226,176,66,.03);
  border: 1.5px dashed rgba(226,176,66,.18);
  color: var(--gold);
  font-family: inherit; font-size: .84rem; font-weight: 800;
  cursor: pointer;
  transition: all .25s var(--ease);
}
.add-college-btn:hover {
  background: rgba(226,176,66,.08);
  border-color: rgba(226,176,66,.4);
  box-shadow: 0 0 20px rgba(226,176,66,.06);
}

/* ── Simple dept/fee rows ── */
.fee-header {
  display: grid; grid-template-columns: 1fr 120px 78px 30px;
  gap: .4rem; margin-bottom: .35rem; padding: 0 1px;
}
.fee-header span { font-size: .64rem; font-weight: 800; color: var(--txt3); }
.fee-row {
  display: grid; grid-template-columns: 1fr 120px 78px 30px;
  gap: .4rem; align-items: center; margin-bottom: .35rem;
}
.fee-row .f-input { margin-bottom: 0; }

@media (max-width: 600px) {
  .college-header-inputs { flex-wrap: wrap; }
  .clg-fee, .clg-disc { width: calc(50% - .2rem); }
  .dept-col-labels, .fee-header { display: none; }
  .dept-row, .fee-row {
    grid-template-columns: 1fr 1fr 30px;
    grid-template-areas: "name name del" "fee disc del";
    row-gap: .6rem; column-gap: .4rem;
    padding: .5rem 0; border-bottom: 1px dashed rgba(255,255,255,0.05);
  }
  .dept-row:last-child, .fee-row:last-child { border-bottom: none; }
  .dept-row .f-input:nth-child(1), .fee-row .f-input:nth-child(1) { grid-area: name; }
  .dept-row .f-input:nth-child(2), .fee-row .f-input:nth-child(2) { grid-area: fee; display: block; }
  .dept-row .f-input:nth-child(3), .fee-row .f-input:nth-child(3) { grid-area: disc; display: block; }
  .dept-row button, .fee-row button { grid-area: del; height: 100%; }
}

/* ════════════════════════════════════════════════
   BUTTONS – Premium
════════════════════════════════════════════════ */
.btn-primary {
  display: inline-flex; align-items: center; justify-content: center; gap: 10px;
  padding: 14px 38px; border-radius: 14px;
  background: linear-gradient(135deg, var(--gold), var(--gold-lt));
  color: #05080f; border: none;
  font-family: inherit; font-size: .96rem; font-weight: 900;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  letter-spacing: .03em;
  box-shadow: 0 6px 20px rgba(226, 176, 66, 0.25);
  position: relative;
  overflow: hidden;
  z-index: 1;
}
.btn-primary::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(135deg, var(--gold-lt), #fff6cc);
  z-index: -1; opacity: 0; transition: opacity 0.3s;
}
.btn-primary::after {
  content: ''; position: absolute; top: -50%; left: -75%;
  width: 50%; height: 200%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
  transform: skewX(-20deg);
  transition: left 0.7s cubic-bezier(0.4, 0, 0.2, 1);
  pointer-events: none;
}
.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(226, 176, 66, 0.4), 0 0 15px rgba(226, 176, 66, 0.25);
}
.btn-primary:hover::before { opacity: 1; }
.btn-primary:hover::after { left: 125%; }
.btn-primary:active { transform: translateY(1px) scale(0.97); box-shadow: 0 4px 10px rgba(226, 176, 66, 0.3); }

.btn-outline {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 22px; border-radius: 12px;
  background: transparent; color: var(--gold);
  border: 1px solid rgba(226,176,66,.2);
  font-family: inherit; font-size: .84rem; font-weight: 700;
  cursor: pointer;
  transition: all .2s var(--ease);
}
.btn-outline:hover {
  background: rgba(226,176,66,.08);
  border-color: rgba(226,176,66,.35);
}

/* ════════════════════════════════════════════════
   POSTS
════════════════════════════════════════════════ */
.posts-grid { display: flex; flex-direction: column; gap: .85rem; }
.p-card {
  background: var(--glass);
  backdrop-filter: blur(10px);
  border: 1px solid var(--border);
  border-radius: 16px;
  display: flex; overflow: hidden;
  transition: all .25s var(--ease);
}
.p-card:hover {
  border-color: rgba(226,176,66,.18);
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(0,0,0,.25);
}
.p-img { width: 100px; flex-shrink: 0; object-fit: cover; }
.p-body { padding: 1.1rem 1.35rem; flex: 1; min-width: 0; }
.p-title { font-weight: 800; font-size: .95rem; color: var(--txt); }
.p-text {
  color: var(--txt2); font-size: .82rem; line-height: 1.65;
  display: -webkit-box; -webkit-line-clamp: 2;
  -webkit-box-orient: vertical; overflow: hidden; margin-top: .25rem;
}
.p-foot {
  display: flex; align-items: center; gap: 8px;
  margin-top: .7rem; flex-wrap: wrap;
}
.p-date { font-size: .72rem; color: var(--txt3); }

/* ── Chips ── */
.chip {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 3px 11px; border-radius: 20px;
  font-size: .71rem; font-weight: 700;
}
.chip-ok {
  background: rgba(45,190,108,.10); color: #2dbe6c;
  border: 1px solid rgba(45,190,108,.2);
  box-shadow: 0 0 8px rgba(45,190,108,.08);
}
.chip-pending {
  background: rgba(255,170,0,.08); color: #f59e0b;
  border: 1px solid rgba(255,170,0,.18);
}
.chip-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

/* ── Empty / Locked ── */
.empty-state {
  text-align: center; padding: 4.5rem 1rem; color: var(--txt3);
}
.empty-icon { font-size: 3.2rem; margin-bottom: 1.1rem; filter: grayscale(.15); }
.empty-txt  { font-size: 1.05rem; font-weight: 800; color: var(--txt2); }
.empty-sub  { font-size: .84rem; margin-top: .45rem; }

.locked-state {
  background: var(--glass);
  backdrop-filter: blur(12px);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 2.75rem; text-align: center; color: var(--txt2);
}
.locked-icon { font-size: 2.2rem; margin-bottom: .75rem; }

/* ════════════════════════════════════════════════
   MOBILE BOTTOM NAV
════════════════════════════════════════════════ */
.db-mobile-nav {
  display: none;
  position: fixed; bottom: 0; left: 0; right: 0;
  background: rgba(12,18,32,.92);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  border-top: 1px solid var(--border);
  z-index: 100; padding: .5rem .25rem env(safe-area-inset-bottom, .5rem);
}
.db-mobile-nav-inner {
  display: flex; justify-content: space-around; align-items: center;
}
.db-mob-btn {
  display: flex; flex-direction: column; align-items: center; gap: 4px;
  padding: .5rem .75rem; border: none; background: transparent;
  color: var(--txt3);
  font-family: inherit; font-size: .67rem; font-weight: 700;
  cursor: pointer; border-radius: 12px; min-width: 62px;
  transition: all .2s var(--ease);
}
.db-mob-btn .mob-icon { font-size: 1.3rem; line-height: 1; }
.db-mob-btn.is-active {
  color: var(--gold);
  background: rgba(226,176,66,.09);
  box-shadow: 0 -2px 12px rgba(226,176,66,.08);
}

/* ════════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════════ */
@media (max-width: 960px) {
  .db-main { padding: 1.75rem 2rem 5rem; }
}
@media (max-width: 768px) {
  .db-side { display: none; }
  .db-mobile-nav { display: block; }
  .db-main { padding: 1.375rem 1.125rem 5.5rem; max-width: 100%; }
  .f-row { gap: .75rem; }
}
@media (max-width: 580px) {
  .f-row { grid-template-columns: 1fr; gap: 0; }
  .db-card { padding: 1.25rem; border-radius: 15px; }
}

/* ── Hide fees for public institutions ── */
#academic-section.hide-fees .college-fee-strip {
  display: none !important;
}
#academic-section.hide-fees .dept-col-labels,
#academic-section.hide-fees .dept-row,
#academic-section.hide-fees .fee-header,
#academic-section.hide-fees .fee-row {
  grid-template-columns: 1fr 30px !important;
}
@media (max-width: 600px) {
  #academic-section.hide-fees .dept-row,
  #academic-section.hide-fees .fee-row {
    grid-template-areas: "name del" !important;
    padding: 0 !important;
    border-bottom: none !important;
  }
}
#academic-section.hide-fees .dept-col-labels span:nth-child(2),
#academic-section.hide-fees .dept-col-labels span:nth-child(3),
#academic-section.hide-fees .fee-header span:nth-child(2),
#academic-section.hide-fees .fee-header span:nth-child(3),
#academic-section.hide-fees .f-input:nth-child(2),
#academic-section.hide-fees .f-input:nth-child(3) {
  display: none !important;
}
#academic-section.hide-fees .dept-col-labels,
#academic-section.hide-fees .dept-row,
#academic-section.hide-fees .fee-header,
#academic-section.hide-fees .fee-row {
  grid-template-columns: 1fr 30px !important;
}
#academic-section.hide-fees .dept-col-labels span:nth-child(2),
#academic-section.hide-fees .dept-col-labels span:nth-child(3),
#academic-section.hide-fees .fee-header span:nth-child(2),
#academic-section.hide-fees .fee-header span:nth-child(3) {
  display: none !important;
}
#academic-section.hide-fees .dept-row .f-input:nth-child(2),
#academic-section.hide-fees .dept-row .f-input:nth-child(3),
#academic-section.hide-fees .fee-row .f-input:nth-child(2),
#academic-section.hide-fees .fee-row .f-input:nth-child(3) {
  display: none !important;
}
</style>
@endsection

@section('content')
<div class="db">

  {{-- ══ SIDEBAR ══ --}}
  <aside class="db-side">
    <div class="db-avatar">
      <div class="db-avatar-circle">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
      <div>
        <div class="db-avatar-name">{{ auth()->user()->name }}</div>
        <div class="db-avatar-email">{{ auth()->user()->email }}</div>
      </div>
    </div>

    <nav class="db-nav">
      <button class="db-nav-btn is-active" onclick="showTab('institution',this)">
        <span class="db-nav-icon">🏫</span> دامەزراوەکەم
      </button>
      <button class="db-nav-btn" onclick="showTab('posts', this)">
        <span class="db-nav-icon">📰</span>
        پۆستەکانم
        @if($posts->count())
          <span class="db-nav-badge">{{ $posts->count() }}</span>
        @endif
      </button>
      <button class="db-nav-btn" onclick="showTab('settings', this)">
        <span class="db-nav-icon">⚙️</span>
        ڕێکخستنەکان
      </button>
      <button class="db-nav-btn" onclick="showTab('new-post',this)">
        <span class="db-nav-icon">✏️</span> پۆستی نوێ
      </button>
    </nav>

    <div class="db-sep" style="margin-top:auto"></div>
    <form method="POST" action="{{ route('portal.logout') }}">
      @csrf
      <button type="submit" class="db-logout-btn">
        <span>🚪</span> دەرچوون
      </button>
    </form>
  </aside>

  {{-- ══ MAIN ══ --}}
  <main class="db-main">



    {{-- ══ TAB: INSTITUTION ══ --}}
    <div class="db-tab is-active" id="tab-institution">
      <div class="pg-head">
        <div class="pg-title">دامەزراوە<span>کەم</span></div>
        <p class="pg-sub">زانیارییەکانت تۆمار بکە تا لە ئەپەکەدا دیار بێت</p>
      </div>

      @if($institution)
        @if(!$institution->approved)
          <div class="nt nt-warn">
            <span class="nt-icon">⏳</span>
            <div>
              <div class="nt-title">چاوەڕوانی پەسەندکردنی ئەدمین</div>
              <div class="nt-sub">پاش پەسەندکردن دەتوانیت پۆست بکەیت</div>
            </div>
          </div>
        @else
          <div class="nt nt-ok">
            <span class="nt-icon">✅</span>
            <span>دامەزراوەکەت پەسەندکراوە — دەتوانیت پۆست بکەیت</span>
          </div>
        @endif
      @endif

      <form id="form-inst" method="POST" action="{{ route('portal.institution.save') }}" enctype="multipart/form-data" onsubmit="handleAjaxSubmit(event, 'btn-save-inst')">
        @csrf

        {{-- ناو --}}
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">📋 ناوی دامەزراوە</div>
            <button type="button" class="btn-tr" onclick="autoTranslate('nku', ['nar', 'nen'], this)">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
              وەرگێڕان
            </button>
          </div>
          <div class="f-row">
            <div class="f-group">
              <label class="f-label">کوردی <span class="f-req">*</span></label>
              <input type="text" id="nku" name="nku" class="f-input" placeholder="ناوی کوردی..." value="{{ old('nku', $institution?->nku) }}" required>
              @error('nku') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div class="f-group">
              <label class="f-label">عەرەبی</label>
              <input type="text" id="nar" name="nar" class="f-input" placeholder="الاسم بالعربي..." value="{{ old('nar', $institution?->nar) }}">
              @error('nar') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div class="f-group">
              <label class="f-label">ئینگلیزی</label>
              <input type="text" id="nen" name="nen" class="f-input" placeholder="English name..." value="{{ old('nen', $institution?->nen) }}" dir="ltr" style="text-align: left;">
              @error('nen') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        {{-- شوێن و جۆر --}}
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">📍 شوێن و جۆر</div>
          </div>
          <div class="f-row">
            <div class="f-group">
              <label class="f-label">جۆری دامەزراوە <span class="f-req">*</span></label>
              <select name="type" class="f-select" required onchange="handleTypeChange(this.value)">
                <option value="">— جۆر هەڵبژێرە —</option>
                @foreach($types as $t)
                  <option value="{{ $t->key }}" {{ old('type', $institution?->type) == $t->key ? 'selected' : '' }}>
                    {{ $t->name }}
                  </option>
                @endforeach
              </select>
              @error('type') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div class="f-group">
              <label class="f-label">وڵات / هەرێم <span class="f-req">*</span></label>
              <select name="country" class="f-input" required>
                <option value="کوردستان" {{ old('country', $institution?->country ?? 'کوردستان') == 'کوردستان' ? 'selected' : '' }}>کوردستان</option>
                <option value="عێراق" {{ old('country', $institution?->country) == 'عێراق' ? 'selected' : '' }}>عێراق</option>
              </select>
              @error('country') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div class="f-group">
              <label class="f-label">شار <span class="f-req">*</span></label>
              <input type="text" name="city" class="f-input" list="cities_list" placeholder="شار هەڵبژێرە یان بنووسە..." value="{{ old('city', $institution?->city) }}" required>
              <datalist id="cities_list">
                @foreach([
                    'هەولێر', 'سلێمانی', 'دهۆک', 'زاخۆ', 'ئامێدی', 'سیمێل', 'شێخان', 'دیانا', 'چۆمان', 'سۆران',
                    'کەرکووک', 'هەڵەبجە', 'رانیە', 'کەلار', 'قلادزێ', 'دوکان', 'دەربەندیخان', 'کفری', 'چەمچەماڵ',
                    'شارەزووری', 'پێنجوێن', 'سەید سادق', 'دوزەخوڕماتو', 'بەغداد', 'مووسڵ', 'بەسرە', 'نەجەف',
                    'کەربەلا', 'حیللە', 'سامەراء', 'تکریت', 'رمادی', 'فەللووجە', 'نەسیریە', 'عەماره', 'کووت',
                    'دیوانیە', 'بعقووبە', 'سینجار', 'تەلاعەفەر'
                ] as $c)
                    <option value="{{ $c }}">
                @endforeach
              </datalist>
              @error('city') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div class="f-group">
              <label class="f-label" style="display: flex; align-items: center; justify-content: space-between;">
                <span>ناونیشان</span>
                <button type="button" onclick="getCurrentLocation(this)" style="background: rgba(196,154,60,.15); color: var(--gold-lt); border: 1px solid var(--border2); padding: 3px 10px; border-radius: 6px; font-size: 0.73rem; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 4px; transition: all 0.2s;">
                   دیاریکردنی شوێن لەسەر ماپ 
                </button>
              </label>
              <input type="text" id="addr-input" name="addr" class="f-input" placeholder="ناونیشانی تەواو بنووسە یان بەستەری نەخشە دابنێ..." oninput="handleAddrInput(this.value)" value="{{ old('addr', $institution?->addr) }}">
              <p id="map-feedback" style="display: none; font-size: 0.73rem; margin-top: 4px; font-weight: bold;"></p>
              @error('addr') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
              
              <!-- Hidden inputs to submit to server -->
              <input type="hidden" id="lat-input" name="lat" value="{{ old('lat', $institution?->lat) }}">
              <input type="hidden" id="lng-input" name="lng" value="{{ old('lng', $institution?->lng) }}">
            </div>
          </div>
        </div>

        {{-- پەیوەندی --}}
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">📞 پەیوەندی</div>
          </div>
          <div class="f-row">
            <div class="f-group">
              <label class="f-label">تەلەفۆن</label>
              <input type="text" name="phone" class="f-input" placeholder="07XX XXX XXXX" value="{{ old('phone', $institution?->phone) }}">
              @error('phone') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div class="f-group">
              <label class="f-label">ئیمەیڵ</label>
              <input type="email" name="email" class="f-input" placeholder="info@example.com" value="{{ old('email', $institution?->email) }}" dir="ltr" style="text-align: left;">
              @error('email') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div class="f-group">
              <label class="f-label">وێبسایت</label>
              <input type="url" name="web" class="f-input" placeholder="https://..." value="{{ old('web', $institution?->web) }}" dir="ltr" style="text-align: left;">
              @error('web') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        {{-- کۆلێژ و بەشەکان --}}
        @php
          $currentType  = old('type', $institution?->type);
          $flags        = $typeFlags[$currentType] ?? ['has_colleges' => false, 'has_departments' => false];
          $showSection  = $flags['has_colleges'] || $flags['has_departments'];
          $showColleges = $flags['has_colleges'];
          $showDepts    = $flags['has_departments'];
          $isPublic     = in_array($currentType, ['gov', 'inst5', 'inst2']);
          // Parse colleges — handles: new JSON (with depts+fee), Filament JSON, legacy newline text
          $collegesData = [];
          if (!empty($institution?->colleges)) {
              $decoded = json_decode($institution->colleges, true);
              if (is_array($decoded) && count($decoded)) {
                  foreach ($decoded as $col) {
                      if (!isset($col['name'])) continue;
                      $depts = [];
                      foreach (($col['depts'] ?? $col['departments'] ?? []) as $d) {
                          $depts[] = [
                              'name'     => is_string($d) ? $d : ($d['name'] ?? $d['dept_name'] ?? ''),
                              'fee'      => $d['fee'] ?? '',
                              'discount' => $d['discount'] ?? '',
                          ];
                      }
                      $collegesData[] = [
                          'name'     => $col['name'],
                          'depts'    => $depts,
                      ];
                  }
              } else {
                  foreach (array_filter(array_map('trim', explode("\n", $institution->colleges))) as $line) {
                      $collegesData[] = ['name' => $line, 'fee' => '', 'discount' => '', 'depts' => []];
                  }
              }
          }
          $nextCiSeed   = count($collegesData);
          // Simple dept rows (for school/non-college types)
          $tuitionList  = is_array($institution?->tuition_plans)
                          ? $institution->tuition_plans
                          : (json_decode($institution?->tuition_plans ?? '[]', true) ?: []);
          $deptsList    = array_filter(array_map('trim', explode("\n", $institution?->depts ?? '')));
          $simpleDeptRows = (count($tuitionList) && !$showColleges)
                          ? $tuitionList
                          : array_map(fn($d) => ['dept' => $d, 'fee' => '', 'discount' => ''], $deptsList);
        @endphp

        <div id="academic-section" class="db-card {{ $isPublic ? 'hide-fees' : '' }}" style="{{ $showSection ? '' : 'display:none' }}">
          <div class="db-card-head">
            <div class="db-card-title">📚 <span id="academic-title">{{ $showColleges ? 'کۆلێژ و بەشەکان' : 'بەشەکان و پارەدان' }}</span></div>
            <button type="button" class="btn-tr" onclick="translateDeptNames(this)">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
              وەرگێڕان
            </button>
          </div>

          {{-- Mode 1: Colleges → nested depts + fee/discount per dept --}}
          <div id="group-colleges" style="{{ $showColleges ? '' : 'display:none' }}">
            <div id="colleges-container" class="college-wrap">
              @forelse($collegesData as $col)
                @php $ci = $loop->index; @endphp
                <div class="college-card" data-ci="{{ $ci }}">
                  <div class="college-header">
                    <span class="college-badge">کۆلێژ</span>
                    <input type="text" name="clg[{{ $ci }}][name]" class="f-input clg-name" value="{{ $col['name'] }}" placeholder="بۆ نموونە: کۆلێژی ئەندازیاری">
                    <button type="button" class="college-del-btn" onclick="removeCollege(this)" title="سڕینەوە">✕</button>
                  </div>
                  <div class="college-body">
                    <div class="depts-header-row">
                      <span class="depts-header-label">بەشەکان</span>
                      <span class="depts-header-line"></span>
                    </div>
                    <div class="dept-col-labels">
                      <span>ناوی بەش</span><span>پارە (دینار)</span><span>داشکان %</span><span></span>
                    </div>
                    <div class="depts-wrap">
                      @forelse($col['depts'] as $dept)
                        @php $di = $loop->index; @endphp
                        <div class="dept-row">
                          <input type="text" name="clg[{{ $ci }}][depts][{{ $di }}][name]" class="f-input" value="{{ $dept['name'] }}" placeholder="بۆ نموونە: بەشی کۆمپیوتەر">
                          <input type="text" name="clg[{{ $ci }}][depts][{{ $di }}][fee]" class="f-input currency-input" value="{{ $dept['fee'] }}" placeholder="پارە (150,000)">
                          <input type="text" name="clg[{{ $ci }}][depts][{{ $di }}][discount]" class="f-input" value="{{ $dept['discount'] }}" placeholder="داشکان (10%)">
                          <button type="button" class="dept-del-btn" onclick="removeDept(this)">✕</button>
                        </div>
                      @empty
                        <div class="dept-row">
                          <input type="text" name="clg[{{ $ci }}][depts][0][name]" class="f-input" placeholder="بۆ نموونە: بەشی کۆمپیوتەر">
                          <input type="text" name="clg[{{ $ci }}][depts][0][fee]" class="f-input currency-input" placeholder="پارە (150,000)">
                          <input type="text" name="clg[{{ $ci }}][depts][0][discount]" class="f-input" placeholder="داشکان (10%)">
                          <button type="button" class="dept-del-btn" onclick="removeDept(this)">✕</button>
                        </div>
                      @endforelse
                    </div>
                    <button type="button" class="add-dept-btn" onclick="addDept(this)">＋ بەش زیاد بکە</button>
                  </div>
                </div>
              @empty
                <div class="college-card" data-ci="0">
                  <div class="college-header">
                    <span class="college-badge">کۆلێژ</span>
                    <input type="text" name="clg[0][name]" class="f-input clg-name" placeholder="بۆ نموونە: کۆلێژی ئەندازیاری">
                    <button type="button" class="college-del-btn" onclick="removeCollege(this)">✕</button>
                  </div>
                  <div class="college-body">
                    <div class="depts-header-row">
                      <span class="depts-header-label">بەشەکان</span>
                      <span class="depts-header-line"></span>
                    </div>
                    <div class="dept-col-labels">
                      <span>ناوی بەش</span><span>پارە (دینار)</span><span>داشکان %</span><span></span>
                    </div>
                    <div class="depts-wrap">
                      <div class="dept-row">
                        <input type="text" name="clg[0][depts][0][name]" class="f-input" placeholder="بۆ نموونە: بەشی کۆمپیوتەر">
                        <input type="text" name="clg[0][depts][0][fee]" class="f-input currency-input" placeholder="پارە (150,000)">
                        <input type="text" name="clg[0][depts][0][discount]" class="f-input" placeholder="داشکان (10%)">
                        <button type="button" class="dept-del-btn" onclick="removeDept(this)">✕</button>
                      </div>
                    </div>
                    <button type="button" class="add-dept-btn" onclick="addDept(this)">＋ بەش زیاد بکە</button>
                  </div>
                </div>
              @endforelse
            </div>
            <button type="button" class="add-college-btn" onclick="addCollege()">🏛️ کۆلێژی نوێ زیاد بکە</button>
          </div>

          {{-- Mode 2: Simple depts + fees (schools etc.) --}}
          <div id="group-depts" style="{{ (!$showColleges && $showDepts) ? '' : 'display:none' }}">
            <div class="fee-header">
              <span>ناوی بەش</span>
              <span>پارە (دینار)</span>
              <span>داشکان %</span>
              <span></span>
            </div>
            <div id="depts-list">
              @forelse($simpleDeptRows as $row)
                <div class="fee-row">
                  <input type="text" name="simple_dept[]" class="f-input" value="{{ $row['dept'] ?? $row['name'] ?? '' }}" placeholder="بۆ نموونە: بەشی کۆمپیوتەر">
                  <input type="text" name="simple_fee[]" class="f-input currency-input" value="{{ $row['fee'] ?? '' }}" placeholder="پارە (150,000)">
                  <input type="text" name="simple_discount[]" class="f-input" value="{{ $row['discount'] ?? '' }}" placeholder="داشکان (10%)">
                  <button type="button" class="dept-del-btn" onclick="removeRow(this)">✕</button>
                </div>
              @empty
                <div class="fee-row">
                  <input type="text" name="simple_dept[]" class="f-input" placeholder="بۆ نموونە: بەشی کۆمپیوتەر">
                  <input type="text" name="simple_fee[]" class="f-input currency-input" placeholder="پارە (150,000)">
                  <input type="text" name="simple_discount[]" class="f-input" placeholder="داشکان (10%)">
                  <button type="button" class="dept-del-btn" onclick="removeRow(this)">✕</button>
                </div>
              @endforelse
            </div>
            <button type="button" class="add-row-btn" onclick="addSimpleDeptRow()">＋ بەش زیاد بکە</button>
          </div>
        </div>

        {{-- دەربارە --}}
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">📝 دەربارە</div>
            <button type="button" class="btn-tr" onclick="autoTranslate('desc', ['desc_ar', 'desc_en'], this)">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
              وەرگێڕان
            </button>
          </div>
          <div class="f-group">
            <label class="f-label">کوردی</label>
            <div id="editor-desc" class="quill-editor">{!! old('desc', $institution?->desc) !!}</div>
            <textarea id="desc" name="desc" style="display:none;"></textarea>
            @error('desc') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
          </div>
          <div class="f-group">
            <label class="f-label">عەرەبی</label>
            <div id="editor-desc_ar" class="quill-editor">{!! old('desc_ar', $institution?->desc_ar) !!}</div>
            <textarea id="desc_ar" name="desc_ar" style="display:none;"></textarea>
            @error('desc_ar') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
          </div>
          <div class="f-group">
            <label class="f-label">ئینگلیزی</label>
            <div id="editor-desc_en" class="quill-editor">{!! old('desc_en', $institution?->desc_en) !!}</div>
            <textarea id="desc_en" name="desc_en" style="display:none;"></textarea>
            @error('desc_en') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- سۆشیال --}}
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">🔗 سۆشیال میدیا</div>
          </div>
          <div class="f-row">
            <div class="f-group">
              <label class="f-label">Facebook</label>
              <input type="url" name="fb" class="f-input" placeholder="https://facebook.com/..." value="{{ old('fb', $institution?->fb) }}" dir="ltr" style="text-align: left;">
              @error('fb') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div class="f-group">
              <label class="f-label">Instagram</label>
              <input type="url" name="ig" class="f-input" placeholder="https://instagram.com/..." value="{{ old('ig', $institution?->ig) }}" dir="ltr" style="text-align: left;">
              @error('ig') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div class="f-group">
              <label class="f-label">ساڵی دامەزراندن</label>
              <input type="number" name="founded_year" class="f-input" placeholder="بۆ نموونە: 2015" value="{{ old('founded_year', $institution?->founded_year) }}">
              @error('founded_year') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        {{-- ڤیدیۆ --}}
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">🎥 بەستەری ناساندن</div>
          </div>
          <div class="f-group">
            <label class="f-label">بەستەر یان لینکی ناساندن</label>
            <input type="url" name="video" class="f-input" placeholder="https://..." value="{{ old('video', $institution?->video) }}">
            @error('video') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- وێنەکان --}}
        <div class="db-card">
          <div class="db-card-head">
            <div class="db-card-title">🖼 وێنەکان</div>
          </div>
          <div class="f-row">
            <div class="f-group">
              <label class="f-label">لۆگۆ</label>
              <label class="f-file" for="logo-input">
                <input type="file" id="logo-input" name="logo" accept="image/*" onchange="previewImg(this,'logo-prev'); document.getElementById('remove_logo').value='0';">
                <div class="f-file-icon">🏷</div>
                <div class="f-file-text">لۆگۆ هەڵبژێرە</div>
                <div class="f-file-hint">PNG, JPG · max 10MB</div>
              </label>
              @error('logo') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
              <input type="hidden" name="remove_logo" id="remove_logo" value="0">
              @if($institution?->logo)
                <div id="logo-wrapper" style="position:relative; display:inline-block; margin-top:.75rem;">
                  <img src="{{ $institution->logo }}" id="logo-prev" class="f-preview" style="display:block; margin-top:0;" alt="">
                  <button type="button" onclick="document.getElementById('logo-wrapper').style.display='none'; document.getElementById('remove_logo').value='1';" style="position:absolute; top:4px; right:4px; background:#ef4444; color:#fff; border:none; border-radius:50%; width:22px; height:22px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:10px;">✕</button>
                </div>
              @else
                <img id="logo-prev" class="f-preview" alt="">
              @endif
            </div>
            <div class="f-group">
              <label class="f-label">وێنەی دامەزراوە</label>
              <label class="f-file" for="img-input">
                <input type="file" id="img-input" name="img" accept="image/*" onchange="previewImg(this,'img-prev'); document.getElementById('remove_img').value='0';">
                <div class="f-file-icon">📸</div>
                <div class="f-file-text">وێنەی سەرەکی هەڵبژێرە</div>
                <div class="f-file-hint">PNG, JPG · max 10MB</div>
              </label>
              @error('img') <div style="color:#ef4444; font-size:.75rem; margin-top:4px;">{{ $message }}</div> @enderror
              <input type="hidden" name="remove_img" id="remove_img" value="0">
              @if($institution?->img)
                <div id="img-wrapper" style="position:relative; display:inline-block; margin-top:.75rem;">
                  <img src="{{ $institution->img }}" id="img-prev" class="f-preview" style="display:block; margin-top:0;" alt="">
                  <button type="button" onclick="document.getElementById('img-wrapper').style.display='none'; document.getElementById('remove_img').value='1';" style="position:absolute; top:4px; right:4px; background:#ef4444; color:#fff; border:none; border-radius:50%; width:22px; height:22px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:10px;">✕</button>
                </div>
              @else
                <img id="img-prev" class="f-preview" alt="">
              @endif
            </div>
          </div>
        </div>

        <div style="display:flex;align-items:center;gap:1rem;margin-top:.5rem;padding-top:1.5rem;border-top:1px solid var(--border)">
          <button type="submit" id="btn-save-inst" class="btn-primary" style="padding:14px 42px;font-size:.95rem">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0" class="btn-icon"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span>پاشەکەوتکردن</span>
          </button>
          <span style="font-size:.78rem;color:var(--txt3);font-weight:600">گۆڕانکارییەکانت خۆکارانە دەنێردرێن</span>
        </div>
      </form>
    </div>

    {{-- ══ TAB: POSTS ══ --}}
    <div class="db-tab" id="tab-posts">
      <div class="pg-head-row">
        <div class="pg-head" style="margin-bottom:0">
          <div class="pg-title">پۆستەکا<span>نم</span></div>
          <p class="pg-sub">{{ $posts->count() }} پۆست بڵاوکراوەتەوە</p>
        </div>
        @if($institution?->approved)
          <button class="btn-primary" style="padding:9px 20px;font-size:.83rem" onclick="showTab('new-post',null);syncMobile('new-post')">+ پۆستی نوێ</button>
        @endif
      </div>

      @forelse($posts as $post)
        <div class="p-card">
          @if($post->image)
            <img src="{{ $post->image }}" class="p-img" alt="">
          @endif
          <div class="p-body">
            <div class="p-title">{{ $post->title }}</div>
            <div class="p-text">{!! Str::limit(strip_tags($post->content), 120) !!}</div>
            <div class="p-foot">
              <span class="chip {{ $post->approved ? 'chip-ok' : 'chip-pending' }}">
                <span class="chip-dot"></span>
                {{ $post->approved ? 'پەسەندکراو' : 'چاوەڕوانی پەسەند' }}
              </span>
              <span class="p-date">{{ $post->created_at->diffForHumans() }}</span>
              <form method="POST" action="{{ route('portal.posts.delete', $post->id) }}" onsubmit="return confirm('دڵنیایت؟')" style="margin-right:auto">
                @csrf @method('DELETE')
                <button type="submit" style="background:none;border:none;cursor:pointer;color:#ff7070;font-size:.78rem;font-family:inherit;font-weight:700">🗑 سڕینەوە</button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <div class="db-card" style="text-align:center;padding:3rem 1rem">
          <div style="font-size:3rem;margin-bottom:1rem;opacity:0.5">📭</div>
          <p style="color:var(--txt2);font-weight:600">هێشتا هیچ پۆستێکت نەکردووە.</p>
        </div>
      @endforelse

      @if($posts->hasPages())
        <div style="margin-top: 1.5rem;">
          {{ $posts->links() }}
        </div>
      @endif
    </div>

    {{-- ══ TAB: SETTINGS ══ --}}
    <div class="db-tab" id="tab-settings">
      <div class="pg-head">
        <div class="pg-title">ڕێکخستنەکان<span>ی هەژمار</span></div>
        <p class="pg-sub">گۆڕینی ناو، ئیمەیڵ و وشەی نهێنی</p>
      </div>

      <div class="db-card">
        <form id="form-settings" method="POST" action="{{ route('portal.settings.save') }}" onsubmit="handleAjaxSubmit(event, 'btn-save-settings')">
          @csrf
          <div class="f-group">
            <label class="f-label">ناوی تەواو <span class="f-req">*</span></label>
            <input type="text" name="name" class="f-input" value="{{ auth()->user()->name }}" required>
          </div>
          <div class="f-group">
            <label class="f-label">ئیمەیڵ <span class="f-req">*</span></label>
            <input type="email" name="email" class="f-input" value="{{ auth()->user()->email }}" dir="ltr" style="text-align: left;" required>
          </div>
          <div class="f-group">
            <label class="f-label">وشەی نهێنی نوێ</label>
            <input type="password" name="password" class="f-input" placeholder="گەر نایگۆڕیت بەتاڵی جێبهێڵە" dir="ltr" style="text-align: left;">
            <div class="f-file-hint" style="margin-top:4px;">لایەنی کەم دەبێت ٨ پیت یان ژمارە بێت</div>
          </div>
          <div style="display:flex;align-items:center;gap:.75rem;margin-top:1.5rem">
            <button type="submit" id="btn-save-settings" class="btn-primary">
              <span class="btn-icon">💾</span> <span>نوێکردنەوەی زانیارییەکان</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- ══ TAB: NEW POST ══ --}}
    <div class="db-tab" id="tab-new-post">
      <div class="pg-head">
        <div class="pg-title">پۆستی <span>نوێ</span></div>
        <p class="pg-sub">هەواڵ، ئیلان یان بابەتێک بڵاوبکەرەوە</p>
      </div>

      @if(!$institution)
        <div class="nt nt-warn"><span class="nt-icon">⚠</span><span>پێشتر دامەزراوەکەت تۆمار بکە.</span></div>
      @elseif(!$institution->approved)
        <div class="locked-state">
          <div class="locked-icon">🔒</div>
          <div style="font-weight:800;color:var(--txt);margin-bottom:.35rem">دامەزراوەکەت هێشتا قبوڵ نەکراوە</div>
          <div style="font-size:.82rem;color:var(--txt3)">پاش قبوڵکردنی ئەدمین دەتوانیت پۆست بکەیت</div>
        </div>
      @else
        <div class="db-card">
          <form id="form-post" method="POST" action="{{ route('portal.posts.store') }}" enctype="multipart/form-data" onsubmit="handleAjaxSubmit(event, 'btn-save-post')">
            @csrf
            <div class="f-group">
              <label class="f-label">ناونیشانی پۆست <span class="f-req">*</span></label>
              <input type="text" name="title" class="f-input" placeholder="ناونیشانی کورت و ڕوون" value="{{ old('title') }}" required>
            </div>
            <div class="f-group">
              <label class="f-label">ناوەڕۆک <span class="f-req">*</span></label>
              <div id="editor-post" class="quill-editor" style="min-height: 200px;">{!! old('content') !!}</div>
              <textarea id="post-content" name="content" style="display:none;"></textarea>
            </div>
            <div class="f-group">
              <label class="f-label">وێنە (ئەختیاری)</label>
              <label class="f-file" for="post-img">
                <input type="file" id="post-img" name="image" accept="image/*" onchange="previewImg(this,'post-prev')">
                <div class="f-file-icon">🖼</div>
                <div class="f-file-text">وێنەی پۆست هەڵبژێرە</div>
                <div class="f-file-hint">PNG, JPG · max 4MB</div>
              </label>
              <img id="post-prev" class="f-preview" alt="">
            </div>
            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-top:.5rem">
              <button type="submit" id="btn-save-post" class="btn-primary">
                <span class="btn-icon">🚀</span> <span>بڵاوکردنەوە</span>
              </button>
              <span style="font-size:.78rem;color:var(--txt3)">ئەدمین پەسەندی دەکات پاش بڵاوکردنەوە</span>
            </div>
          </form>
        </div>
      @endif
    </div>

  </main>
</div>

{{-- ══ MOBILE BOTTOM NAV ══ --}}
<nav class="db-mobile-nav">
  <div class="db-mobile-nav-inner">
    <button class="db-mob-btn is-active" id="mob-institution" onclick="showTab('institution',null);syncMobile('institution')">
      <span class="mob-icon">🏫</span>دامەزراوەکەم
    </button>
    <button class="db-mob-btn" id="mob-posts" onclick="showTab('posts',null);syncMobile('posts')">
      <span class="mob-icon">📰</span>پۆستەکانم
    </button>
    <button class="db-mob-btn" id="mob-new-post" onclick="showTab('new-post',null);syncMobile('new-post')">
      <span class="mob-icon">✏️</span>پۆستی نوێ
    </button>
    <form method="POST" action="{{ route('portal.logout') }}" style="display:contents">
      @csrf
      <button type="submit" class="db-mob-btn">
        <span class="mob-icon">🚪</span>دەرچوون
      </button>
    </form>
  </div>
</nav>

@endsection

@section('scripts')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
  .ql-toolbar.ql-snow {
    border: 1px solid var(--border) !important;
    border-top-left-radius: var(--radius-sm);
    border-top-right-radius: var(--radius-sm);
    background: rgba(15, 22, 36, 0.6);
    direction: ltr; /* Quill toolbar is LTR */
  }
  .ql-container.ql-snow {
    border: 1px solid var(--border) !important;
    border-top: none !important;
    border-bottom-left-radius: var(--radius-sm);
    border-bottom-right-radius: var(--radius-sm);
    background: rgba(6, 10, 18, 0.4);
    font-family: inherit; font-size: .95rem; color: var(--txt);
    min-height: 120px;
  }
  .ql-editor { direction: rtl; text-align: right; }
  .ql-stroke { stroke: var(--txt2) !important; }
  .ql-fill { fill: var(--txt2) !important; }
  .ql-picker-label { color: var(--txt2) !important; }
  .ql-active .ql-stroke { stroke: var(--gold-lt) !important; }
  .ql-active .ql-fill { fill: var(--gold-lt) !important; }
</style>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
// Format Currency Inputs
function formatCurrency(val) {
    if (!val) return '';
    const num = val.toString().replace(/[^0-9]/g, '');
    if (!num) return '';
    return Number(num).toLocaleString('en-US');
}
function attachCurrencyFormatter() {
    document.querySelectorAll('.currency-input').forEach(input => {
        // Format on load
        input.value = formatCurrency(input.value);
        // Format on input
        input.addEventListener('input', function(e) {
            let start = this.selectionStart;
            let val = this.value;
            let formatted = formatCurrency(val);
            let diff = formatted.length - val.length;
            this.value = formatted;
            this.setSelectionRange(start + diff, start + diff);
        });
    });
}

// Handle Form Submission (Loading State) via AJAX
async function handleAjaxSubmit(e, btnId) {
    e.preventDefault();
    const form = e.target;

    // Sync Quill Editors
    if (quillEditors['desc']) document.getElementById('desc').value = quillEditors['desc'].root.innerHTML;
    if (quillEditors['desc_ar']) document.getElementById('desc_ar').value = quillEditors['desc_ar'].root.innerHTML;
    if (quillEditors['desc_en']) document.getElementById('desc_en').value = quillEditors['desc_en'].root.innerHTML;
    if (quillEditors['post'] && document.getElementById('post-content')) document.getElementById('post-content').value = quillEditors['post'].root.innerHTML;

    const btn = document.getElementById(btnId);
    let originalHtml = '';
    if (btn) {
        originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.classList.add('loading');
        const icon = btn.querySelector('.btn-icon');
        if (icon) icon.style.display = 'none';
        btn.querySelector('span').innerHTML = '⏳ چاوەڕوان بە...';
    }

    try {
        const formData = new FormData(form);
        const res = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        
        if (res.ok) {
            showToast(data.message || 'سەرکەوتوو بوو', 'success');
            if (form.id === 'form-post' || form.id === 'form-settings') {
                setTimeout(() => location.reload(), 1500);
            }
        } else {
            let msg = data.message || 'هەڵەیەک ڕوویدا';
            if (data.errors) {
                const firstKey = Object.keys(data.errors)[0];
                msg = data.errors[firstKey][0];
            }
            showToast(msg, 'error');
        }
    } catch (err) {
        showToast('هێڵی ئینتەرنێتەکەت کێشەی هەیە یان سێرڤەر وەڵام ناداتەوە', 'error');
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('loading');
            btn.innerHTML = originalHtml;
        }
    }
}

let quillEditors = {};

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Quill
    const toolbarOptions = [
      ['bold', 'italic', 'underline'],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['link'],
      ['clean']
    ];
    
    if (document.getElementById('editor-desc')) {
        quillEditors['desc'] = new Quill('#editor-desc', { theme: 'snow', modules: { toolbar: toolbarOptions }});
        quillEditors['desc_ar'] = new Quill('#editor-desc_ar', { theme: 'snow', modules: { toolbar: toolbarOptions }});
        quillEditors['desc_en'] = new Quill('#editor-desc_en', { theme: 'snow', modules: { toolbar: toolbarOptions }});
    }
    if (document.getElementById('editor-post')) {
        quillEditors['post'] = new Quill('#editor-post', { theme: 'snow', modules: { toolbar: toolbarOptions }});
    }

    attachCurrencyFormatter();

    // Restore active tab
    const activeTab = localStorage.getItem('db_active_tab') || 'institution';
    const activeBtn = document.querySelector(`[onclick="showTab('${activeTab}', this)"]`);
    if (activeBtn) activeBtn.click();
    else showTab('institution', document.querySelector('.db-nav-btn'));
});

function showTab(name, sideBtn) {
    document.querySelectorAll('.db-tab').forEach(p => p.classList.remove('is-active'));
    document.querySelectorAll('.db-nav-btn').forEach(b => b.classList.remove('is-active'));
    const tab = document.getElementById('tab-' + name);
    if(tab) tab.classList.add('is-active');
    if (sideBtn) sideBtn.classList.add('is-active');
    localStorage.setItem('db_active_tab', name);
}
function syncMobile(name) {
    document.querySelectorAll('.db-mob-btn').forEach(b => b.classList.remove('is-active'));
    const mob = document.getElementById('mob-' + name);
    if (mob) mob.classList.add('is-active');
}
function previewImg(input, previewId) {
    const file = input.files[0];
    if (!file) return;
    if (file.size > 10 * 1024 * 1024) {
        if(typeof showToast === 'function') showToast('گەورەترین قەبارەی ڕێگەپێدراو 10 مێگابایتە', 'error');
        else alert('گەورەترین قەبارەی ڕێگەپێدراو 10 مێگابایتە');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById(previewId);
        if (img) { img.src = e.target.result; img.style.display = 'block'; }
        if(input.id === 'logo-input' && document.getElementById('logo-wrapper')) document.getElementById('logo-wrapper').style.display = 'inline-block';
        if(input.id === 'img-input' && document.getElementById('img-wrapper')) document.getElementById('img-wrapper').style.display = 'inline-block';
    };
    reader.readAsDataURL(file);
}
async function translateDeptNames(btn) {
    const section = document.getElementById('academic-section');
    const isCollege = document.getElementById('group-colleges').style.display !== 'none';
    let inputs = [];
    if (isCollege) {
        section.querySelectorAll('.college-card .clg-name').forEach(i => inputs.push(i));
        section.querySelectorAll('.college-card .dept-row .f-input:first-child').forEach(i => inputs.push(i));
    } else {
        section.querySelectorAll('#depts-list .fee-row .f-input:first-child').forEach(i => inputs.push(i));
    }
    inputs = inputs.filter(i => i.value.trim());
    if (!inputs.length) { alert('تکایە سەرەتا ناوی بەشەکان بنووسە.'); return; }
    btn.classList.add('loading'); btn.disabled = true;
    try {
        for (const inp of inputs) {
            const text = inp.value.trim();
            if (!text) continue;
            let hint = inp.nextElementSibling;
            if (!hint || !hint.classList.contains('tr-hint')) {
                hint = document.createElement('small');
                hint.className = 'tr-hint';
                hint.style.cssText = 'display:block;font-size:.68rem;color:var(--txt3);margin-top:3px;direction:rtl;line-height:1.7';
                inp.after(hint);
            }
            hint.textContent = '⏳ وەرگێران...';
            const [arRes, enRes] = await Promise.all([
                fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=ckb&tl=ar&dt=t&q=${encodeURIComponent(text)}`).then(r => r.json()),
                fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=ckb&tl=en&dt=t&q=${encodeURIComponent(text)}`).then(r => r.json()),
            ]);
            const ar = arRes?.[0]?.map(p => p[0] ?? '').join('') ?? '';
            const en = enRes?.[0]?.map(p => p[0] ?? '').join('') ?? '';
            hint.innerHTML = `<span style="color:var(--gold);font-weight:800">AR</span> ${ar}&nbsp;&nbsp;<span style="color:var(--gold);font-weight:800">EN</span> ${en}`;
        }
    } catch { alert('هەڵەیەک ڕوویدا لە کاتی وەرگێڕان.'); }
    finally { btn.classList.remove('loading'); btn.disabled = false; }
}
async function autoTranslate(sourceId, targetIds, btn) {
    const text = document.getElementById(sourceId).value;
    if (!text) { alert('تکایە سەرەتا دەقەکە بنووسە.'); return; }
    btn.classList.add('loading'); btn.disabled = true;
    try {
        for (const targetId of targetIds) {
            const lang = targetId.includes('ar') ? 'ar' : 'en';
            const url  = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=ckb&tl=${lang}&dt=t&q=${encodeURIComponent(text)}`;
            const data = await (await fetch(url)).json();
            if (data?.[0]) document.getElementById(targetId).value = data[0].map(p => p[0] ?? '').join('');
        }
    } catch { alert('هەڵەیەک ڕوویدا لە کاتی وەرگێڕان.'); }
    finally { btn.classList.remove('loading'); btn.disabled = false; }
}
const TYPE_FLAGS = @json($typeFlags);
function handleTypeChange(type) {
    const section  = document.getElementById('academic-section');
    const grpCol   = document.getElementById('group-colleges');
    const grpDept  = document.getElementById('group-depts');
    const title    = document.getElementById('academic-title');
    const flags    = TYPE_FLAGS[type] || { has_colleges: false, has_departments: false };
    if (!flags.has_colleges && !flags.has_departments) {
        section.style.display = 'none'; return;
    }
    section.style.display = '';
    title.textContent     = flags.has_colleges ? 'کۆلێژ و بەشەکان' : 'بەشەکان و پارەدان';
    grpCol.style.display  = flags.has_colleges ? '' : 'none';
    grpDept.style.display = (!flags.has_colleges && flags.has_departments) ? '' : 'none';

    if (['gov', 'inst5', 'inst2'].includes(type)) {
        section.classList.add('hide-fees');
        // Clear fee/discount inputs so they do not submit stale values
        section.querySelectorAll('.dept-row input:nth-child(2), .dept-row input:nth-child(3), .fee-row input:nth-child(2), .fee-row input:nth-child(3)').forEach(inp => {
            inp.value = '';
        });
    } else {
        section.classList.remove('hide-fees');
    }
}
let _nextCi = {{ $nextCiSeed ?? 1 }};
function addCollege() {
    const container = document.getElementById('colleges-container');
    const ci = _nextCi++;
    const card = document.createElement('div');
    card.className = 'college-card';
    card.dataset.ci = ci;
    card.innerHTML =
        `<div class="college-header">` +
          `<span class="college-badge">کۆلێژ</span>` +
          `<input type="text" name="clg[${ci}][name]" class="f-input clg-name" placeholder="بۆ نموونە: کۆلێژی ئەندازیاری">` +
          `<button type="button" class="college-del-btn" onclick="removeCollege(this)">✕</button>` +
        `</div>` +
        `<div class="college-body">` +
          `<div class="depts-header-row">` +
            `<span class="depts-header-label">بەشەکان</span>` +
            `<span class="depts-header-line"></span>` +
          `</div>` +
          `<div class="dept-col-labels">` +
            `<span>ناوی بەش</span><span>پارە (دینار)</span><span>داشکان %</span><span></span>` +
          `</div>` +
          `<div class="depts-wrap">` +
            `<div class="dept-row">` +
              `<input type="text" name="clg[${ci}][depts][0][name]" class="f-input" placeholder="بۆ نموونە: بەشی کۆمپیوتەر">` +
              `<input type="text" name="clg[${ci}][depts][0][fee]" class="f-input" placeholder="پارە (150,000)">` +
              `<input type="text" name="clg[${ci}][depts][0][discount]" class="f-input" placeholder="داشکان (10%)">` +
              `<button type="button" class="dept-del-btn" onclick="removeDept(this)">✕</button>` +
            `</div>` +
          `</div>` +
          `<button type="button" class="add-dept-btn" onclick="addDept(this)">＋ بەش زیاد بکە</button>` +
        `</div>`;
    container.appendChild(card);
    card.querySelector('input').focus();
}
function removeCollege(btn) {
    const card = btn.closest('.college-card');
    if (card.parentElement.children.length > 1) card.remove();
    else card.querySelectorAll('input').forEach(i => i.value = '');
}
function addDept(btn) {
    const card = btn.closest('.college-card');
    const ci   = card.dataset.ci;
    const wrap = card.querySelector('.depts-wrap');
    const di   = wrap.children.length;
    const row  = document.createElement('div');
    row.className = 'dept-row';
    row.innerHTML =
        `<input type="text" name="clg[${ci}][depts][${di}][name]" class="f-input" placeholder="بۆ نموونە: بەشی کۆمپیوتەر">` +
        `<input type="text" name="clg[${ci}][depts][${di}][fee]" class="f-input" placeholder="پارە (150,000)">` +
        `<input type="text" name="clg[${ci}][depts][${di}][discount]" class="f-input" placeholder="داشکان (10%)">` +
        `<button type="button" class="dept-del-btn" onclick="removeDept(this)">✕</button>`;
    wrap.appendChild(row);
    row.querySelector('input').focus();
}
function removeDept(btn) {
    const row = btn.parentElement;
    if (row.parentElement.children.length > 1) row.remove();
    else row.querySelectorAll('input').forEach(i => i.value = '');
}
function addSimpleDeptRow() {
    const list = document.getElementById('depts-list');
    const row  = document.createElement('div');
    row.className = 'fee-row';
    row.innerHTML =
        `<input type="text" name="simple_dept[]" class="f-input" placeholder="بۆ نموونە: بەشی کۆمپیوتەر">` +
        `<input type="text" name="simple_fee[]" class="f-input" placeholder="پارە (150,000)">` +
        `<input type="text" name="simple_discount[]" class="f-input" placeholder="داشکان (10%)">` +
        `<button type="button" class="dept-del-btn" onclick="removeRow(this)">✕</button>`;
    list.appendChild(row);
    row.querySelector('input').focus();
}
function removeRow(btn) {
    const row  = btn.parentElement;
    const list = row.parentElement;
    if (list.children.length > 1) row.remove();
    else row.querySelectorAll('input').forEach(i => i.value = '');
}
function formatShortAddress(data) {
    if (!data || !data.address) return data.display_name || '';
    const addr = data.address;
    const parts = [];
    
    // 1. Street or neighbourhood/suburb
    const local = addr.road || addr.suburb || addr.neighbourhood || addr.quarter || addr.residential || addr.industrial;
    if (local) parts.push(local);
    
    // 2. City or Town
    const city = addr.city || addr.town || addr.village || addr.municipality || addr.county;
    if (city && city !== local) parts.push(city);
    
    // 3. State/Region or Country
    const region = addr.state || addr.country;
    if (region && region !== city) parts.push(region);
    
    return parts.length > 0 ? parts.join(', ') : (data.display_name || '');
}
function handleAddrInput(value) {
    if (!value) {
        document.getElementById('lat-input').value = '';
        document.getElementById('lng-input').value = '';
        document.getElementById('map-feedback').style.display = 'none';
        return;
    }
    const regex = /(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/;
    const match = value.match(regex);
    if (match) {
        const lat = match[1];
        const lng = match[2];
        document.getElementById('lat-input').value = lat;
        document.getElementById('lng-input').value = lng;
        const fb = document.getElementById('map-feedback');
        fb.style.display = 'block';
        fb.textContent = '✓ کۆۆردیناتەکان دۆزرانەوە، ئێستا ناونیشانی دەقی وەردەگیرێت...';
        fb.style.color = '#3b82f6';
        
        const addrField = document.getElementById('addr-input');
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=ku,ar,en`)
            .then(response => response.json())
            .then(data => {
                const shortAddr = formatShortAddress(data);
                if (shortAddr) {
                    addrField.value = shortAddr;
                    fb.textContent = '✓ کۆۆردینات و ناونیشان بە سەرکەوتوویی وەرگیران!';
                    fb.style.color = '#22c55e';
                }
            })
            .catch(err => {
                fb.textContent = '✓ کۆۆردیناتەکان بە سەرکەوتوویی پارێزراون.';
                fb.style.color = '#22c55e';
            });
    } else {
        document.getElementById('map-feedback').style.display = 'none';
    }
}
function getCurrentLocation(btn) {
    if (!navigator.geolocation) {
        alert('مۆبایلەکەت یان گەڕانکارەکەت پشتگیری وەرگرتنی شوێن ناکات.');
        return;
    }
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ لە پرۆسەدایە...';
    btn.disabled = true;
    
    const addrField = document.getElementById('addr-input');
    addrField.placeholder = '⏳ بەدەستهێنانی ناونیشانی دەقی لە نەخشەوە...';
    
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude.toFixed(6);
            const lng = position.coords.longitude.toFixed(6);
            document.getElementById('lat-input').value = lat;
            document.getElementById('lng-input').value = lng;
            
            const fb = document.getElementById('map-feedback');
            fb.style.display = 'block';
            fb.textContent = '✓ کۆۆردینات بە سەرکەوتوویی وەرگیرا. ئێستا ناونیشانی دەقی وەردەگیرێت...';
            fb.style.color = '#3b82f6';
            
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=ku,ar,en`)
                .then(response => response.json())
                .then(data => {
                    const shortAddr = formatShortAddress(data);
                    if (shortAddr) {
                        addrField.value = shortAddr;
                        fb.textContent = '✓ شوێن و ناونیشانی دەقیت بە سەرکەوتوویی وەرگیرا لە نەخشەوە!';
                        fb.style.color = '#22c55e';
                    } else {
                        fb.textContent = '✓ کۆۆردینات وەرگیرا، بەڵام نەتوانرا ناونیشانی دەقی دیاری بکرێت.';
                        fb.style.color = '#ff9f43';
                    }
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    addrField.placeholder = 'ناونیشانی تەواو بنووسە یان بەستەری نەخشە دابنێ...';
                })
                .catch(err => {
                    fb.textContent = '✓ کۆۆردینات وەرگیرا، بەڵام پەیوەندی بە نەخشەوە نەکرا بۆ وەرگرتنی ناونیشانی دەقی.';
                    fb.style.color = '#ff9f43';
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    addrField.placeholder = 'ناونیشانی تەواو بنووسە یان بەستەری نەخشە دابنێ...';
                });
        },
        (error) => {
            let msg = 'نەتوانرا شوێنەکەت دیاری بکرێت.';
            if (error.code === error.PERMISSION_DENIED) {
                msg = 'تکایە ڕێگەبدە بە بەکارهێنانی لۆکەیشن بۆ ئەم ماڵپەڕە تاوەکو شوێنەکەت وەربگیرێت.';
            }
            alert(msg);
            addrField.placeholder = 'ناونیشانی تەواو بنووسە یان بەستەری نەخشە دابنێ...';
            btn.innerHTML = originalText;
            btn.disabled = false;
        },
        { enableHighAccuracy: true, timeout: 8000 }
    );
}
</script>
@endsection
