<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Editor de plantillas · posMe</title>
<style>
:root{
  --bg:#f3f4f6; --panel:#ffffff; --line:#e3e6eb; --ink:#1c2230; --muted:#6b7280;
  --accent:#0f6fde; --accent-soft:#e8f1fd; --warn:#9a4a07; --warn-bg:#fff6eb; --err:#b42318; --err-bg:#fef0ee;
  --ok:#157a3c; --code-bg:#111827; --code-ink:#e5e7eb; --gutter:#6b7280; --loop:#fdf3c4;
}
*{box-sizing:border-box}
[hidden]{display:none!important}
html,body{height:100%;margin:0}
body{font:13.5px/1.45 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;color:var(--ink);background:var(--bg);display:flex;flex-direction:column}
header{display:flex;align-items:center;gap:8px;flex-wrap:wrap;padding:8px 12px;background:var(--panel);border-bottom:1px solid var(--line)}
header h1{font-size:15px;margin:0 10px 0 0;white-space:nowrap;font-weight:650}
header h1 b{color:var(--accent)}
.grp{display:flex;gap:4px;align-items:center;flex-wrap:wrap}
.sep{width:1px;height:24px;background:var(--line);margin:0 4px}
.spacer{flex:1}
button,select,input[type=text]{font:inherit;border:1px solid var(--line);background:#fff;border-radius:6px;padding:5px 10px;color:var(--ink);height:30px}
button{cursor:pointer;white-space:nowrap}
button:hover:not(:disabled){border-color:#c3c8d1;background:#f8f9fb}
button.primary{background:var(--accent);border-color:var(--accent);color:#fff}
button.primary:hover:not(:disabled){background:#0c62c6}
button.danger{color:var(--err)}
button:disabled{opacity:.45;cursor:default}
button.icon{padding:5px 8px;min-width:30px}
input[type=text]{width:190px}
main{flex:1;display:grid;grid-template-columns:minmax(0,1.3fr) 250px minmax(0,1fr);min-height:0}
section{display:flex;flex-direction:column;min-height:0;min-width:0;background:var(--panel);border-right:1px solid var(--line)}
section:last-child{border-right:0;background:#e9ebef}
.tabs{display:flex;gap:2px;padding:6px 8px 0;border-bottom:1px solid var(--line);background:var(--panel)}
.tab{border:1px solid transparent;border-bottom:0;border-radius:6px 6px 0 0;background:transparent;height:32px;padding:5px 14px;color:var(--muted);font-weight:550}
.tab.active{background:var(--panel);border-color:var(--line);color:var(--ink);margin-bottom:-1px;height:33px}
.tab:hover:not(.active){background:#f3f4f6}
.pane{flex:1;display:none;min-height:0;flex-direction:column}
.pane.active{display:flex}
/* código */
.codewrap{flex:1;display:flex;min-height:0;background:var(--code-bg)}
.gutter{padding:10px 8px 10px 10px;color:var(--gutter);text-align:right;font:12.5px/1.55 ui-monospace,SFMono-Regular,Consolas,monospace;user-select:none;overflow:hidden;white-space:pre;min-width:42px;border-right:1px solid #1f2937}
textarea.code{flex:1;border:0;outline:0;resize:none;background:transparent;color:var(--code-ink);padding:10px 12px;font:12.5px/1.55 ui-monospace,SFMono-Regular,Consolas,monospace;white-space:pre;overflow:auto;tab-size:2}
textarea.json{flex:1;border:0;outline:0;resize:none;padding:10px 12px;font:12.5px/1.55 ui-monospace,SFMono-Regular,Consolas,monospace;white-space:pre;overflow:auto;background:#fbfbfc;color:var(--ink)}
.subbar{display:flex;align-items:center;gap:6px;flex-wrap:wrap;padding:6px 8px;border-bottom:1px solid var(--line);background:#fafbfc}
.subbar .hint{color:var(--muted);font-size:12px}
/* visual */
.visualwrap{flex:1;overflow:auto;background:#e9ebef;padding:18px;display:flex;justify-content:center;align-items:flex-start}
.visualwrap iframe,.previewwrap iframe{border:0;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.12),0 6px 18px rgba(0,0,0,.06);display:block}
/* barra lateral */
aside{background:#fafbfc;border-right:1px solid var(--line);overflow:auto;padding:10px}
aside h3{font-size:11.5px;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);margin:12px 0 6px;font-weight:650}
aside h3:first-child{margin-top:2px}
.chips{display:flex;flex-wrap:wrap;gap:4px}
.chip{font:12px ui-monospace,SFMono-Regular,Consolas,monospace;border:1px solid #cfd9e8;background:var(--accent-soft);color:#0b4f9e;border-radius:5px;padding:2px 6px;height:auto;cursor:pointer}
.chip.unused{background:#fff;color:var(--muted);border-style:dashed}
.chip.missing{background:var(--warn-bg);border-color:#f3c690;color:var(--warn)}
.loopbox{border:1px solid #ecd98a;background:var(--loop);border-radius:6px;padding:6px;margin:6px 0}
.loopbox .lname{font:600 12px ui-monospace,SFMono-Regular,Consolas,monospace;margin-bottom:4px;display:flex;justify-content:space-between;align-items:center;gap:4px}
.loopbox .lname button{height:22px;padding:0 6px;font-size:11px}
.msg{border-radius:6px;padding:7px 8px;margin:6px 0;font-size:12.5px}
.msg.warn{background:var(--warn-bg);color:var(--warn);border:1px solid #f6d7b0}
.msg.err{background:var(--err-bg);color:var(--err);border:1px solid #f6c9c3}
.msg.ok{background:#edf8f1;color:var(--ok);border:1px solid #bfe5cc}
.msg button{height:24px;padding:0 8px;font-size:12px;margin-top:5px}
.muted{color:var(--muted);font-size:12px}
/* vista previa */
.previewbar{display:flex;align-items:center;gap:6px;flex-wrap:wrap;padding:6px 8px;border-bottom:1px solid var(--line);background:var(--panel)}
.previewbar strong{font-size:13px;margin-right:auto}
.previewwrap{flex:1;overflow:auto;padding:18px;display:flex;justify-content:center;align-items:flex-start}
.badge{font-size:11px;padding:2px 6px;border-radius:10px;background:#eef0f3;color:var(--muted)}
.badge.server{background:#e6f4ea;color:var(--ok)}
.toast{position:fixed;bottom:16px;left:50%;transform:translateX(-50%);background:#1c2230;color:#fff;padding:8px 14px;border-radius:8px;font-size:13px;opacity:0;pointer-events:none;transition:opacity .2s}
.toast.show{opacity:.95}
label.chk{display:flex;align-items:center;gap:4px;font-size:12.5px;color:var(--muted)}
@media (max-width:1150px){
  main{display:block;overflow:auto}
  section,aside{border-right:0;border-bottom:1px solid var(--line)}
  .pane{min-height:0}
  .visualwrap,.previewwrap{overflow:visible;flex:none}
  .codewrap{height:70vh;flex:none}
  textarea.json{height:60vh;flex:none}
}
</style>
</head>
<body>
<script>window.POSME_EDITOR = <?= json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;</script>
<header>
  <h1>Editor de plantillas <b>posMe</b></h1>
  <div class="grp" id="serverGroup" hidden>
    <select id="tplList" title="Plantillas guardadas"></select>
    <input type="text" id="tplName" placeholder="nombre_plantilla" title="Nombre con el que se guarda">
    <button class="primary" id="btnSave" title="Guardar en posMe (Ctrl+S)">Guardar</button>
    <span class="sep"></span>
  </div>
  <div class="grp">
    <button class="icon" id="btnUndo" title="Deshacer">↶</button>
    <button class="icon" id="btnRedo" title="Rehacer">↷</button>
    <span class="sep"></span>
    <button id="btnCopy" title="Copiar el HTML al portapapeles">Copiar HTML</button>
    <button id="btnPaste" title="Reemplazar la plantilla con lo que tengas copiado">Pegar plantilla</button>
    <button id="btnDownload" title="Descargar como archivo .html">Descargar</button>
    <button id="btnImport" title="Abrir un archivo .html">Abrir archivo</button>
    <input type="file" id="fileInput" accept=".html,.htm,.txt,.php" hidden>
    <button class="danger" id="btnReset" title="Volver a la plantilla original">Restaurar</button>
  </div>
</header>

<main>
  <section>
    <div class="tabs">
      <button class="tab active" data-tab="visual">Visual</button>
      <button class="tab" data-tab="code">Código</button>
      <button class="tab" data-tab="data">Datos de prueba</button>
    </div>

    <div class="pane active" id="pane-visual">
      <div class="subbar" id="vtools">
        <button class="icon" data-cmd="bold" title="Negrita"><b>B</b></button>
        <button class="icon" data-cmd="italic" title="Cursiva"><i>I</i></button>
        <button class="icon" data-cmd="underline" title="Subrayado"><u>U</u></button>
        <span class="sep"></span>
        <button class="icon" data-cmd="left" title="Alinear a la izquierda">⇤</button>
        <button class="icon" data-cmd="center" title="Centrar">↔</button>
        <button class="icon" data-cmd="right" title="Alinear a la derecha">⇥</button>
        <span class="sep"></span>
        <button class="icon" data-cmd="smaller" title="Letra más pequeña">A−</button>
        <button class="icon" data-cmd="bigger" title="Letra más grande">A+</button>
        <span class="sep"></span>
        <button data-cmd="rowAdd" title="Duplicar la fila debajo (vacía)">+ Fila</button>
        <button data-cmd="rowBlank" title="Insertar una fila en blanco debajo">+ Espacio</button>
        <button data-cmd="rowLine" title="Insertar una línea separadora debajo">+ Línea</button>
        <button data-cmd="rowUp" class="icon" title="Subir fila">▲</button>
        <button data-cmd="rowDown" class="icon" title="Bajar fila">▼</button>
        <button data-cmd="rowDel" class="danger" title="Eliminar la fila">Eliminar fila</button>
      </div>
      <div class="visualwrap"><iframe id="visual" sandbox="allow-same-origin" title="Edición visual"></iframe></div>
    </div>

    <div class="pane" id="pane-code">
      <div class="subbar"><span class="hint">Escribe o pega el HTML. Las variables van entre llaves: <code>{companyName}</code>. Los bucles: <code>&lt;!--{detalle}--&gt; … &lt;!--{/detalle}--&gt;</code></span></div>
      <div class="codewrap">
        <div class="gutter" id="gutter">1</div>
        <textarea class="code" id="code" spellcheck="false" autocomplete="off" autocapitalize="off"></textarea>
      </div>
    </div>

    <div class="pane" id="pane-data">
      <div class="subbar">
        <span class="hint">Valores de ejemplo para ver la vista previa (formato JSON). No se guardan en la plantilla.</span>
        <span class="spacer"></span>
        <button id="btnFillData" title="Agregar al JSON las variables de la plantilla que no tienen dato">Completar faltantes</button>
        <button id="btnFormatData">Ordenar JSON</button>
      </div>
      <textarea class="json" id="data" spellcheck="false"></textarea>
    </div>
  </section>

  <aside id="side">
    <h3>Revisión</h3>
    <div id="checks"></div>
    <h3>Variables</h3>
    <div class="muted" style="margin-bottom:6px">Clic para insertar donde está el cursor.</div>
    <div class="chips" id="vars"></div>
    <div id="loops"></div>
    <div id="unusedWrap" hidden>
      <h3>Disponibles sin usar</h3>
      <div class="chips" id="unused"></div>
    </div>
  </aside>

  <section>
    <div class="previewbar">
      <strong>Vista previa</strong>
      <span class="badge" id="renderBadge">navegador</span>
      <select id="paper" title="Ancho del papel">
        <option value="auto">Ancho: según @page</option>
        <option value="58mm">58 mm</option>
        <option value="2.7in">2.7 in (≈69 mm)</option>
        <option value="80mm">80 mm</option>
        <option value="8.5in">Carta</option>
      </select>
      <select id="zoom" title="Zoom">
        <option value="1">100%</option>
        <option value="1.25" selected>125%</option>
        <option value="1.5">150%</option>
        <option value="2">200%</option>
      </select>
    </div>
    <div class="previewwrap"><iframe id="preview" sandbox="allow-same-origin" title="Vista previa"></iframe></div>
  </section>
</main>
<div class="toast" id="toast"></div>

<script>
(function(){
'use strict';
var CFG = window.POSME_EDITOR || null;

/* ---------- plantilla y datos por defecto ---------- */
var DEFAULT_TEMPLATE = [
"<!DOCTYPE html>",
"<html lang='en'>",
"<head>",
"  <meta charset='UTF-8' />",
"  <meta name='viewport' content='width=device-width, initial-scale=1.0' />",
"  <style>",
"\t@page {",
"\t  size: 2.7in 55in;",
"\t  margin-top:0px;",
"\t  margin-left:10px;",
"\t  margin-right:0px;",
"\t}",
"\ttable{",
"\t  font-size: 9pt;",
"\t  font-weight: bold;",
"\t  font-family: 'DejaVu Sans', sans-serif;",
"\t}",
"  </style>",
"</head>",
"<body>",
"  <table style='width:100%'>",
"\t<tr><td colspan='3' style='text-align:center'><img src='{imageBase64}' width='130' ></td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>{companyName}</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>{address}</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>TEL: {phoneNumber}</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>RUC: {companyRuc}</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>&nbsp;</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>RECIBO DE ABONO</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'># {transactionNumber}</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>FECHA: {transactionDate}</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>HORA: {transactionTime}</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>&nbsp;</td></tr>",
"\t<tr><td colspan='1'>Cliente:</td><td colspan='2'>{customerName}</td></tr>",
"\t<tr><td colspan='1'>Codigo:</td><td colspan='2'>{customerNumber}</td></tr>",
"\t<tr><td colspan='1'>Moneda:</td><td colspan='2'>{currencySimbol}</td></tr>",
"\t<tr><td colspan='1'>Tipo Pago:</td><td colspan='2'>{paymentTypeName}</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>&nbsp;</td></tr>",
"\t<tr>",
"\t  <td style=\"text-align:left;\" colspan=\"1\">DOC</td>",
"\t  <td style=\"text-align:right;\" colspan=\"1\">ABONO</td>",
"\t  <td style=\"text-align:right;\" colspan=\"1\">SALDO</td>",
"\t</tr>",
"\t<!--{transactionMasterDetail}-->",
"\t<tr>",
"\t  <td style=\"text-align:left;\" colspan=\"3\">{itemName}</td>",
"\t</tr>",
"\t<tr>",
"\t  <td colspan=\"1\" style=\"text-align:left;\">{itemDocumentNumber}</td>",
"\t  <td colspan=\"1\" style=\"text-align:right;\">{itemAmountPayment}</td>",
"\t  <td colspan=\"1\" style=\"text-align:right;\">{itemAmountBalance}</td>",
"\t</tr>",
"\t<!--{/transactionMasterDetail}-->",
"\t<tr><td colspan='3' style='text-align:center'>&nbsp;</td></tr>",
"\t<tr><td colspan='2'>SALDO INICIAL</td><td style='text-align:right'>{currencySimbol} {amount_initial_balance}</td></tr>",
"\t<tr><td colspan='2'>TOTAL ABONO</td><td style='text-align:right'>{currencySimbol} {amount_total_payment}</td></tr>",
"\t<tr><td colspan='2'>SALDO FINAL</td><td style='text-align:right'>{currencySimbol} {amount_final_balance}</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>&nbsp;</td></tr>",
"\t<tr><td colspan='3' style='text-align:center'>posMe PRO Premium 3.1</td></tr>",
"  </table>",
"</body>",
"</html>"
].join("\n");

function sampleLogo(){
  var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="260" height="90" viewBox="0 0 260 90">' +
    '<rect x="2" y="2" width="256" height="86" rx="12" fill="#fff" stroke="#111" stroke-width="4"/>' +
    '<text x="130" y="58" font-family="Arial, sans-serif" font-size="34" font-weight="700" text-anchor="middle" fill="#111">TU LOGO</text></svg>';
  return 'data:image/svg+xml;base64,' + btoa(svg);
}
var DEFAULT_DATA = {
  imageBase64: sampleLogo(),
  companyName: "FERRETERÍA EL PROGRESO",
  address: "Managua, de la rotonda 2 c. al sur",
  phoneNumber: "2255-0000",
  companyRuc: "J0310000000000",
  transactionNumber: "ABO-000125",
  transactionDate: "28/11/2026",
  transactionTime: "12:57 PM",
  customerName: "María López",
  customerNumber: "CLI00042",
  currencySimbol: "C$",
  paymentTypeName: "EFECTIVO",
  transactionMasterDetail: [
    { itemName: "Factura de crédito", itemDocumentNumber: "FAC-00310", itemAmountPayment: "1,500.00", itemAmountBalance: "3,250.00" },
    { itemName: "Factura de crédito", itemDocumentNumber: "FAC-00342", itemAmountPayment: "750.00", itemAmountBalance: "500.00" }
  ],
  amount_initial_balance: "6,000.00",
  amount_total_payment: "2,250.00",
  amount_final_balance: "3,750.00"
};
if (CFG && CFG.sampleData && typeof CFG.sampleData === 'object') DEFAULT_DATA = CFG.sampleData;
var ORIGINAL_TEMPLATE = (CFG && CFG.initialTemplate) ? CFG.initialTemplate : DEFAULT_TEMPLATE;

/* ---------- almacenamiento local (borrador) ---------- */
var STORE = 'posmeTplEditor:' + ((CFG && CFG.storeKey) || 'local') + ':';
function sget(k){ try { return localStorage.getItem(STORE + k); } catch(e){ return null; } }
function sset(k,v){ try { localStorage.setItem(STORE + k, v); } catch(e){} }

/* ---------- utilidades ---------- */
var $ = function(id){ return document.getElementById(id); };
function reEsc(s){ return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }
function escHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
var toastT;
function toast(msg){ var t=$('toast'); t.textContent=msg; t.classList.add('show'); clearTimeout(toastT); toastT=setTimeout(function(){ t.classList.remove('show'); },1800); }
function debounce(fn, ms){ var t; return function(){ var a=arguments, s=this; clearTimeout(t); t=setTimeout(function(){ fn.apply(s,a); }, ms); }; }

/* ---------- parser compatible con CodeIgniter 4 (View Parser) ---------- */
var RESERVED = {'if':1,'elseif':1,'else':1,'endif':1,'noparse':1};
function applyFilters(val, filters){
  if (!filters) return val;
  filters.split('|').filter(Boolean).forEach(function(f){
    var m = f.match(/^(\w+)(?:\((.*)\))?$/); if (!m) return;
    var name = m[1], arg = m[2];
    switch(name){
      case 'upper': val = String(val).toUpperCase(); break;
      case 'lower': val = String(val).toLowerCase(); break;
      case 'title': val = String(val).toLowerCase().replace(/(^|\s)\S/g, function(c){ return c.toUpperCase(); }); break;
      case 'esc': val = escHtml(val); break;
      case 'nl2br': val = String(val).replace(/\r?\n/g,'<br>'); break;
      case 'default': if (val === '' || val == null) val = (arg||'').replace(/^['"]|['"]$/g,''); break;
      case 'number_format':
        var n = parseFloat(String(val).replace(/,/g,'')); var d = parseInt(arg||'0',10)||0;
        if (!isNaN(n)) val = n.toLocaleString('en-US',{minimumFractionDigits:d,maximumFractionDigits:d});
        break;
    }
  });
  return val;
}
function renderBlock(tpl, data){
  Object.keys(data).forEach(function(k){
    var v = data[k];
    if (Array.isArray(v)) {
      var re = new RegExp('\\{' + reEsc(k) + '\\}([\\s\\S]+?)\\{\\/' + reEsc(k) + '\\}', 'g');
      tpl = tpl.replace(re, function(m, inner){
        return v.map(function(row){
          var scope = {}; Object.keys(data).forEach(function(x){ if (x !== k) scope[x] = data[x]; });
          if (row && typeof row === 'object' && !Array.isArray(row)) Object.keys(row).forEach(function(x){ scope[x] = row[x]; });
          return renderBlock(inner, scope);
        }).join('');
      });
    } else if (v && typeof v === 'object') {
      /* objeto: CI4 lo trata como un par de una sola fila */
      var re2 = new RegExp('\\{' + reEsc(k) + '\\}([\\s\\S]+?)\\{\\/' + reEsc(k) + '\\}', 'g');
      tpl = tpl.replace(re2, function(m, inner){ return renderBlock(inner, v); });
    }
  });
  Object.keys(data).forEach(function(k){
    var v = data[k];
    if (v === null || typeof v !== 'object') {
      var re = new RegExp('\\{\\s*' + reEsc(k) + '((?:\\|\\w+(?:\\([^)]*\\))?)*)\\s*\\}', 'g');
      tpl = tpl.replace(re, function(m, f){ return applyFilters(v == null ? '' : String(v), f); });
    }
  });
  return tpl;
}
function ciParse(tpl, data){
  var keep = [];
  tpl = tpl.replace(/\{noparse\}([\s\S]*?)\{\/noparse\}/g, function(m, c){ keep.push(c); return '\u0000NP' + (keep.length-1) + '\u0000'; });
  tpl = tpl.replace(/\{#[\s\S]*?#\}/g, '');
  tpl = renderBlock(tpl, data || {});
  return tpl.replace(/\u0000NP(\d+)\u0000/g, function(m, i){ return keep[+i]; });
}

/* ---------- análisis de la plantilla ---------- */
function analyze(t){
  var res = { vars: [], loops: [], issues: [] };
  var body = t.replace(/\{noparse\}[\s\S]*?\{\/noparse\}/g, '');
  var closeNames = {}; var m;
  var reClose = /\{\/(\w+)\}/g;
  while ((m = reClose.exec(body))) closeNames[m[1]] = (closeNames[m[1]]||0) + 1;
  var openCount = {};
  Object.keys(closeNames).forEach(function(n){
    if (RESERVED[n]) return;
    var c = body.match(new RegExp('\\{' + reEsc(n) + '\\}', 'g')); openCount[n] = c ? c.length : 0;
  });
  var loopNames = Object.keys(closeNames).filter(function(n){ return !RESERVED[n]; });
  loopNames.forEach(function(n){
    if (openCount[n] !== closeNames[n]) {
      res.issues.push({ type:'err', text:'El bucle <b>{' + escHtml(n) + '}</b> tiene ' + openCount[n] + ' apertura(s) y ' + closeNames[n] + ' cierre(s).' });
    }
  });
  var inner = {};
  loopNames.forEach(function(n){
    var re = new RegExp('\\{' + reEsc(n) + '\\}([\\s\\S]*?)\\{\\/' + reEsc(n) + '\\}', 'g'); var mm; var set = {};
    while ((mm = re.exec(body))) { var r2 = /\{\s*([A-Za-z_][\w]*)\s*(?:\|[^{}]*)?\}/g, x; while ((x = r2.exec(mm[1]))) if (!RESERVED[x[1]] && loopNames.indexOf(x[1]) < 0) set[x[1]] = 1; }
    inner[n] = Object.keys(set);
    var unprot = new RegExp('(?<!<!--\\s*)\\{\\/?' + reEsc(n) + '\\}', 'g');
    if (unprot.test(body)) res.unprotected = (res.unprotected||[]).concat(n);
  });
  var top = {}; var r = /\{\s*([A-Za-z_][\w]*)\s*(?:\|[^{}]*)?\}/g;
  var stripped = body;
  loopNames.forEach(function(n){ stripped = stripped.replace(new RegExp('\\{' + reEsc(n) + '\\}[\\s\\S]*?\\{\\/' + reEsc(n) + '\\}', 'g'), ' '); });
  while ((m = r.exec(stripped))) if (!RESERVED[m[1]] && loopNames.indexOf(m[1]) < 0) top[m[1]] = 1;
  res.vars = Object.keys(top);
  res.loops = loopNames.map(function(n){ return { name:n, vars: inner[n] || [] }; });
  return res;
}
function protectLoops(t, names){
  names.forEach(function(n){
    t = t.replace(new RegExp('(?<!<!--\\s*)\\{(\\/?)' + reEsc(n) + '\\}(?!\\s*-->)', 'g'), '<!--{$1' + n + '}-->');
  });
  return t;
}

/* ---------- estado ---------- */
var EMBED = !!(CFG && CFG.embed);
var tpl = sget('tpl') || ORIGINAL_TEMPLATE;
var dataText = sget('data') || JSON.stringify(DEFAULT_DATA, null, 2);
var mode = 'visual';
var hist = [tpl], hIdx = 0, histTimer = null;
var codeEl = $('code'), dataEl = $('data'), visualEl = $('visual'), previewEl = $('preview');
var lastInfo = analyze(tpl);
var lastFocus = 'visual';

function pushHistory(){
  clearTimeout(histTimer); histTimer = null;
  if (hist[hIdx] === tpl) return;
  hist = hist.slice(0, hIdx + 1); hist.push(tpl);
  if (hist.length > 200) hist.shift();
  hIdx = hist.length - 1; updUndo();
}
function recordSoon(){ clearTimeout(histTimer); histTimer = setTimeout(pushHistory, 700); }
function updUndo(){ $('btnUndo').disabled = hIdx <= 0 && hist[hIdx] === tpl; $('btnRedo').disabled = hIdx >= hist.length - 1; }

function setTemplate(t, opts){
  opts = opts || {};
  tpl = t;
  if (opts.from !== 'code') { codeEl.value = tpl; updGutter(); }
  if (opts.from !== 'visual' && mode === 'visual') loadVisual();
  if (opts.record === 'now') pushHistory(); else if (opts.record !== false) recordSoon();
  sset('tpl', tpl);
  refreshSide(); scheduleRender(); updUndo();
}

/* ---------- editor de código ---------- */
function updGutter(){
  var n = codeEl.value.split('\n').length, s = '';
  for (var i = 1; i <= n; i++) s += i + '\n';
  $('gutter').textContent = s;
}
codeEl.addEventListener('input', function(){ setTemplate(codeEl.value, { from:'code' }); });
codeEl.addEventListener('scroll', function(){ $('gutter').scrollTop = codeEl.scrollTop; });
codeEl.addEventListener('focus', function(){ lastFocus = 'code'; });
codeEl.addEventListener('keydown', function(e){
  if (e.key === 'Tab') { e.preventDefault(); document.execCommand('insertText', false, '  '); }
});

/* ---------- editor visual ---------- */
var EDITOR_CSS =
  'html{background:#fff}' +
  'body[contenteditable]{outline:none;min-height:100px}' +
  'td,th{outline:1px dashed rgba(15,111,222,.22);outline-offset:-1px;overflow-wrap:anywhere;word-break:break-word}' +
  '[data-ed-loop]{background:#fdf3c4}' +
  '[data-ed-loop] td{outline-color:rgba(180,130,0,.55)}' +
  '[data-ed-cur]>td{background:rgba(15,111,222,.07)}' +
  'img{min-width:40px;min-height:24px;outline:1px dashed #94a3b8}';

function paperWidth(t){
  var sel = $('paper').value;
  if (sel !== 'auto') return sel;
  var pg = t.match(/@page\s*\{([^}]*)\}/i);
  if (pg) {
    var sz = pg[1].match(/size\s*:\s*([\d.]+)\s*(in|mm|cm|px|pt)/i);
    if (sz) return sz[1] + sz[2];
    if (/size\s*:\s*(letter|carta)/i.test(pg[1])) return '8.5in';
    if (/size\s*:\s*a4/i.test(pg[1])) return '210mm';
  }
  return '80mm';
}
function pageMargins(t){
  var pg = t.match(/@page\s*\{([^}]*)\}/i), out = { top:'0', right:'0', bottom:'0', left:'0' };
  if (!pg) return null;
  var all = pg[1].match(/(?:^|[;\s])margin\s*:\s*([^;]+)/i);
  if (all) { var p = all[1].trim().split(/\s+/); out.top = p[0]; out.right = p[1]||p[0]; out.bottom = p[2]||p[0]; out.left = p[3]||p[1]||p[0]; }
  ['top','right','bottom','left'].forEach(function(s){ var m = pg[1].match(new RegExp('margin-' + s + '\\s*:\\s*([^;]+)','i')); if (m) out[s] = m[1].trim(); });
  return out;
}
function screenCss(t){
  var m = pageMargins(t);
  return m ? '@media screen{body{margin:' + m.top + ' ' + m.right + ' ' + m.bottom + ' ' + m.left + '}}' : '';
}

function loadVisual(){
  var names = analyze(tpl).loops.map(function(l){ return l.name; });
  var prot = protectLoops(tpl, names);
  if (prot !== tpl) {
    tpl = prot; codeEl.value = tpl; updGutter(); sset('tpl', tpl); pushHistory();
    toast('Marcadores de bucle protegidos con <!-- -->');
  }
  visualEl.style.width = paperWidth(tpl);
  visualEl.style.zoom = $('zoom').value;
  visualEl.srcdoc = tpl;
}
var visualSyncing = false;
visualEl.addEventListener('load', function(){
  var d = visualEl.contentDocument; if (!d || !d.body) return;
  var st = d.createElement('style'); st.id = '__ed_style'; st.textContent = EDITOR_CSS + screenCss(tpl);
  (d.head || d.documentElement).appendChild(st);
  d.body.setAttribute('contenteditable', 'true');
  try { d.execCommand('styleWithCSS', false, false); } catch(e){}
  markLoops(d);
  d.addEventListener('input', debounce(syncFromVisual, 250));
  d.addEventListener('focusin', function(){ lastFocus = 'visual'; });
  d.addEventListener('mousedown', function(){ lastFocus = 'visual'; });
  ['keyup','mouseup'].forEach(function(ev){ d.addEventListener(ev, markCurrentRow); });
  d.addEventListener('keydown', function(e){
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') { e.preventDefault(); doSave(); }
  });
  d.addEventListener('paste', function(e){
    /* pegar solo texto para no meter estilos de otras páginas */
    var txt = (e.clipboardData || window.clipboardData).getData('text/plain');
    e.preventDefault(); d.execCommand('insertText', false, txt);
  });
  fitFrame(visualEl); setTimeout(function(){ fitFrame(visualEl); }, 300);
});
function markLoops(d){
  var w = d.createTreeWalker(d.body, NodeFilter.SHOW_COMMENT, null), c, opens = [];
  while ((c = w.nextNode())) {
    var m = c.data.trim().match(/^\{(\/?)(\w+)\}$/); if (!m) continue;
    if (!m[1]) opens.push(c);
    else {
      for (var i = opens.length - 1; i >= 0; i--) {
        var o = opens[i];
        if (o.data.trim() === '{' + m[2] + '}' && o.parentNode === c.parentNode) {
          var n = o.nextSibling;
          while (n && n !== c) { if (n.nodeType === 1) n.setAttribute('data-ed-loop', m[2]); n = n.nextSibling; }
          opens.splice(i, 1); break;
        }
      }
    }
  }
}
function markCurrentRow(){
  var d = visualEl.contentDocument; if (!d) return;
  d.querySelectorAll('[data-ed-cur]').forEach(function(e){ e.removeAttribute('data-ed-cur'); });
  var c = curCell(); if (c && c.parentNode) c.parentNode.setAttribute('data-ed-cur', '1');
}
function serializeVisual(){
  var d = visualEl.contentDocument; if (!d) return tpl;
  var root = d.documentElement.cloneNode(true);
  var st = root.querySelector('#__ed_style'); if (st) st.remove();
  root.querySelectorAll('[contenteditable]').forEach(function(e){ e.removeAttribute('contenteditable'); });
  root.querySelectorAll('[data-ed-loop]').forEach(function(e){ e.removeAttribute('data-ed-loop'); });
  root.querySelectorAll('[data-ed-cur]').forEach(function(e){ e.removeAttribute('data-ed-cur'); });
  root.querySelectorAll('[style=""]').forEach(function(e){ e.removeAttribute('style'); });
  var dt = /^\s*<!doctype[^>]*>/i.exec(tpl);
  return (dt ? dt[0].trim() : '<!DOCTYPE html>') + '\n' + root.outerHTML + '\n';
}
function syncFromVisual(){
  if (mode !== 'visual') return;
  var out = serializeVisual();
  if (out !== tpl) setTemplate(out, { from:'visual' });
  fitFrame(visualEl);
}
function vdoc(){ return visualEl.contentDocument; }
function curCell(){
  var d = vdoc(); if (!d) return null;
  var s = d.getSelection(); if (!s || !s.rangeCount) return null;
  var n = s.anchorNode; if (n && n.nodeType === 3) n = n.parentNode;
  return (n && n.closest) ? n.closest('td,th') : null;
}
function colCount(table){
  var max = 1;
  table.querySelectorAll('tr').forEach(function(tr){
    var s = 0; tr.querySelectorAll(':scope>td,:scope>th').forEach(function(c){ s += parseInt(c.getAttribute('colspan')||'1',10)||1; });
    if (s > max) max = s;
  });
  return max;
}
function placeCaret(el){
  var d = vdoc(), r = d.createRange(); r.selectNodeContents(el); r.collapse(true);
  var s = d.getSelection(); s.removeAllRanges(); s.addRange(r);
}
function runCmd(cmd){
  var d = vdoc(); if (!d) return;
  var cell = curCell();
  var needCell = ['left','center','right','smaller','bigger','rowAdd','rowBlank','rowLine','rowDel','rowUp','rowDown'].indexOf(cmd) >= 0;
  if (needCell && !cell) { toast('Primero haz clic dentro de una celda del recibo'); return; }
  var tr = cell ? cell.parentNode : null;
  switch(cmd){
    case 'bold': case 'italic': case 'underline': d.execCommand(cmd); break;
    case 'left': case 'center': case 'right': cell.style.textAlign = cmd; break;
    case 'smaller': case 'bigger':
      var px = parseFloat(visualEl.contentWindow.getComputedStyle(cell).fontSize) || 12;
      var pt = Math.round(px * 0.75 + (cmd === 'bigger' ? 1 : -1));
      cell.style.fontSize = Math.max(5, pt) + 'pt'; break;
    case 'rowAdd':
      var nr = tr.cloneNode(true);
      nr.querySelectorAll(':scope>td,:scope>th').forEach(function(c){ c.innerHTML = '&nbsp;'; });
      tr.after(nr); placeCaret(nr.firstElementChild); break;
    case 'rowBlank': case 'rowLine':
      var table = tr.closest('table'), n = colCount(table);
      var row = d.createElement('tr'), td = d.createElement('td');
      td.setAttribute('colspan', String(n)); td.style.textAlign = 'center';
      if (cmd === 'rowBlank') td.innerHTML = '&nbsp;';
      else { td.style.borderTop = '1px dashed #000'; td.innerHTML = '&nbsp;'; }
      row.appendChild(td); tr.after(row); placeCaret(td); break;
    case 'rowUp':
      var p = tr.previousElementSibling; if (p) tr.parentNode.insertBefore(tr, p); break;
    case 'rowDown':
      var nx = tr.nextElementSibling; if (nx) tr.parentNode.insertBefore(nx, tr); break;
    case 'rowDel':
      var next = tr.nextElementSibling || tr.previousElementSibling;
      tr.remove(); if (next && next.firstElementChild) placeCaret(next.firstElementChild); break;
  }
  markLoops(d); markCurrentRow();
  var out = serializeVisual();
  if (out !== tpl) setTemplate(out, { from:'visual', record:'now' });
  fitFrame(visualEl);
}
document.querySelectorAll('#vtools [data-cmd]').forEach(function(b){
  b.addEventListener('mousedown', function(e){ e.preventDefault(); });
  b.addEventListener('click', function(){ runCmd(b.getAttribute('data-cmd')); });
});

function insertVar(text){
  if (mode === 'visual' && vdoc()) {
    var d = vdoc(); visualEl.contentWindow.focus();
    if (!curCell()) { toast('Haz clic dentro de una celda y vuelve a elegir la variable'); return; }
    d.execCommand('insertText', false, text);
    syncFromVisual();
  } else if (mode === 'code') {
    codeEl.focus(); document.execCommand('insertText', false, text);
  } else {
    navigator.clipboard && navigator.clipboard.writeText(text);
    toast(text + ' copiado');
  }
}

/* ---------- barra lateral ---------- */
function dataObj(){ try { return { ok:true, v: JSON.parse(dataText) }; } catch(e){ return { ok:false, err:e.message }; } }
function refreshSide(){
  var info = analyze(tpl); lastInfo = info;
  var d = dataObj(), data = d.ok ? d.v : {};
  var checks = $('checks'); checks.innerHTML = '';
  function add(type, html, btnText, fn){
    var div = document.createElement('div'); div.className = 'msg ' + type; div.innerHTML = html;
    if (btnText) { var b = document.createElement('button'); b.textContent = btnText; b.onclick = fn; div.appendChild(document.createElement('br')); div.appendChild(b); }
    checks.appendChild(div);
  }
  info.issues.forEach(function(i){ add(i.type, i.text); });
  if (info.unprotected && info.unprotected.length) {
    add('warn', 'Los marcadores de <b>' + info.unprotected.map(function(n){ return '{' + escHtml(n) + '}'; }).join(', ') +
      '</b> están sueltos entre filas. Al editar visualmente se moverían fuera de la tabla.', 'Protegerlos con <!-- -->', function(){
        setTemplate(protectLoops(tpl, info.unprotected), { record:'now' });
      });
  }
  if (!d.ok) add('err', 'Los datos de prueba no son JSON válido: ' + escHtml(d.err));
  var missing = [];
  if (d.ok) {
    info.vars.forEach(function(v){ if (!(v in data)) missing.push(v); });
    info.loops.forEach(function(l){
      var arr = data[l.name];
      if (!Array.isArray(arr)) { missing.push(l.name + '[]'); return; }
      l.vars.forEach(function(v){ if (!arr.some(function(r){ return r && v in r; }) && !(v in data)) missing.push(l.name + '.' + v); });
    });
    if (missing.length) add('warn', missing.length + ' variable(s) sin dato de prueba: <code>' + missing.map(escHtml).join(', ') + '</code>. En la vista previa se ven tal cual entre llaves.', 'Completar datos', fillData);
  }
  if (!/@page/i.test(tpl)) add('warn', 'La plantilla no tiene regla <code>@page</code>; el tamaño de impresión lo decidirá el generador de PDF.');
  if (!checks.children.length) add('ok', 'Todo en orden. La plantilla es compatible con el Parser de CodeIgniter 4.');

  var vars = $('vars'); vars.innerHTML = '';
  info.vars.forEach(function(v){ vars.appendChild(chip(v, missing.indexOf(v) >= 0 ? 'missing' : '')); });
  if (!info.vars.length) vars.innerHTML = '<span class="muted">Sin variables.</span>';

  var loops = $('loops'); loops.innerHTML = '';
  info.loops.forEach(function(l){
    var box = document.createElement('div'); box.className = 'loopbox';
    var head = document.createElement('div'); head.className = 'lname';
    head.innerHTML = '<span>↻ ' + escHtml(l.name) + '</span>';
    var b = document.createElement('button'); b.textContent = 'Insertar bucle'; b.title = 'Inserta un bloque de fila repetible en el código';
    b.onclick = function(){ insertLoopSnippet(l.name, l.vars); };
    head.appendChild(b); box.appendChild(head);
    var c = document.createElement('div'); c.className = 'chips';
    l.vars.forEach(function(v){ c.appendChild(chip(v, missing.indexOf(l.name + '.' + v) >= 0 ? 'missing' : '')); });
    if (!l.vars.length) c.innerHTML = '<span class="muted">Sin variables dentro.</span>';
    box.appendChild(c);
    var hint = document.createElement('div'); hint.className = 'muted'; hint.style.marginTop = '4px';
    hint.textContent = 'Las filas amarillas se repiten una vez por cada registro.'; box.appendChild(hint);
    loops.appendChild(box);
  });

  var used = {}; info.vars.forEach(function(v){ used[v] = 1; }); info.loops.forEach(function(l){ used[l.name] = 1; l.vars.forEach(function(v){ used[v] = 1; }); });
  var un = $('unused'); un.innerHTML = ''; var anyUnused = false;
  if (d.ok) Object.keys(data).forEach(function(k){
    if (used[k]) return;
    if (Array.isArray(data[k])) {
      anyUnused = true;
      var b = chip(k, 'unused', '↻ ' + k);
      b.onclick = function(){ var row = data[k][0] || {}; insertLoopSnippet(k, Object.keys(row)); };
      un.appendChild(b);
    } else if (data[k] === null || typeof data[k] !== 'object') { anyUnused = true; un.appendChild(chip(k, 'unused')); }
  });
  $('unusedWrap').hidden = !anyUnused;
}
function chip(name, cls, label){
  var b = document.createElement('button'); b.className = 'chip ' + (cls||''); b.textContent = label || ('{' + name + '}');
  b.title = 'Insertar {' + name + '}';
  b.addEventListener('mousedown', function(e){ e.preventDefault(); });
  b.onclick = function(){ insertVar('{' + name + '}'); };
  return b;
}
function insertLoopSnippet(name, vars){
  var cells = (vars.length ? vars : ['campo']).map(function(v){ return '\t  <td>{' + v + '}</td>'; }).join('\n');
  var snip = '\n\t<!--{' + name + '}-->\n\t<tr>\n' + cells + '\n\t</tr>\n\t<!--{/' + name + '}-->\n';
  if (mode !== 'code') switchTab('code');
  codeEl.focus(); document.execCommand('insertText', false, snip);
  toast('Bucle insertado donde estaba el cursor');
}
function fillData(){
  var d = dataObj(); if (!d.ok) { toast('Corrige primero el JSON'); return; }
  var data = d.v, info = analyze(tpl);
  info.vars.forEach(function(v){ if (!(v in data)) data[v] = '[' + v + ']'; });
  info.loops.forEach(function(l){
    if (!Array.isArray(data[l.name])) data[l.name] = [{}, {}];
    if (!data[l.name].length) data[l.name].push({});
    data[l.name].forEach(function(row, i){ if (row && typeof row === 'object') l.vars.forEach(function(v){ if (!(v in row)) row[v] = '[' + v + ' ' + (i+1) + ']'; }); });
  });
  setData(JSON.stringify(data, null, 2));
  toast('Datos de prueba completados');
}

/* ---------- datos de prueba ---------- */
function setData(t){ dataText = t; dataEl.value = t; sset('data', t); refreshSide(); scheduleRender(); }
dataEl.addEventListener('input', function(){ dataText = dataEl.value; sset('data', dataText); refreshSide(); scheduleRender(); });
$('btnFillData').onclick = fillData;
$('btnFormatData').onclick = function(){ var d = dataObj(); if (d.ok) setData(JSON.stringify(d.v, null, 2)); else toast('El JSON tiene errores'); };

/* ---------- vista previa ---------- */
var renderSeq = 0;
function fitFrame(f){
  try {
    var d = f.contentDocument; if (!d || !d.body) return;
    var b = d.body, cs = f.contentWindow.getComputedStyle(b);
    var h = Math.ceil(b.getBoundingClientRect().bottom + (parseFloat(cs.marginBottom) || 0) + (d.documentElement.scrollTop || 0));
    if (h > 0 && Math.abs(h + 4 - f.offsetHeight) > 2) f.style.height = (h + 4) + 'px';
  } catch(e){}
}
function injectScreen(html){
  var css = '<style>' + screenCss(tpl) + 'html{background:#fff}</style>';
  return /<\/head>/i.test(html) ? html.replace(/<\/head>/i, css + '</head>') : css + html;
}
function showPreview(html, server){
  previewEl.style.width = paperWidth(tpl);
  previewEl.style.zoom = $('zoom').value;
  previewEl.srcdoc = injectScreen(html);
  var b = $('renderBadge'); b.textContent = server ? 'servidor CI4' : 'navegador'; b.className = 'badge' + (server ? ' server' : '');
  b.title = server ? 'Generada con \\Config\\Services::parser() de tu servidor' : 'Generada en el navegador con un parser compatible';
}
previewEl.addEventListener('load', function(){ fitFrame(previewEl); setTimeout(function(){ fitFrame(previewEl); }, 300); });
function render(){
  var d = dataObj(); if (!d.ok) return;
  var seq = ++renderSeq;
  if (CFG && CFG.previewUrl) {
    fetch(CFG.previewUrl, { method:'POST', headers: hdrs(), body: JSON.stringify({ template: tpl, data: d.v }), credentials:'same-origin' })
      .then(function(r){ if (!r.ok) throw new Error(r.status); return r.json(); })
      .then(function(j){
        if (seq !== renderSeq) return;
        if (j.csrf) updCsrf(j.csrf);
        /* error: se usa el parser del navegador; sin datos: vista previa vacia; con datos: HTML del servidor */
        if (!j || j.error === true || j.ok === false) throw new Error((j && j.message) || 'error');
        showPreview(typeof j.html === 'string' ? j.html : '', true);
      })
      .catch(function(){ if (seq === renderSeq) showPreview(ciParse(tpl, d.v), false); });
  } else showPreview(ciParse(tpl, d.v), false);
}
var scheduleRender = debounce(render, 300);
$('paper').onchange = function(){ if (mode === 'visual') { visualEl.style.width = paperWidth(tpl); fitFrame(visualEl); } render(); };
$('zoom').onchange = function(){ visualEl.style.zoom = $('zoom').value; render(); };

/* ---------- pestañas ---------- */
function switchTab(t){
  if (mode === 'visual' && t !== 'visual') { var out = serializeVisual(); if (out !== tpl) setTemplate(out, { from:'visual', record:'now' }); }
  mode = t;
  document.querySelectorAll('.tab').forEach(function(b){ b.classList.toggle('active', b.getAttribute('data-tab') === t); });
  document.querySelectorAll('.pane').forEach(function(p){ p.classList.toggle('active', p.id === 'pane-' + t); });
  if (t === 'visual') loadVisual();
  if (t === 'code') { codeEl.value = tpl; updGutter(); }
}
document.querySelectorAll('.tab').forEach(function(b){ b.onclick = function(){ switchTab(b.getAttribute('data-tab')); }; });

/* ---------- deshacer / rehacer ---------- */
$('btnUndo').onclick = function(){
  if (histTimer) pushHistory();
  if (hIdx > 0) { hIdx--; setTemplate(hist[hIdx], { record:false, from: mode === 'visual' ? null : null }); }
};
$('btnRedo').onclick = function(){ if (hIdx < hist.length - 1) { hIdx++; setTemplate(hist[hIdx], { record:false }); } };

/* ---------- copiar / pegar / descargar / abrir ---------- */
function currentTemplate(){ if (mode === 'visual') { var o = serializeVisual(); if (o !== tpl) setTemplate(o, { from:'visual', record:'now' }); } return tpl; }
$('btnCopy').onclick = function(){
  var t = currentTemplate();
  var done = function(){ toast('HTML copiado al portapapeles'); };
  if (navigator.clipboard && navigator.clipboard.writeText) navigator.clipboard.writeText(t).then(done, fallback); else fallback();
  function fallback(){ var ta = document.createElement('textarea'); ta.value = t; document.body.appendChild(ta); ta.select(); try { document.execCommand('copy'); done(); } catch(e){ toast('No se pudo copiar'); } ta.remove(); }
};
$('btnPaste').onclick = function(){
  if (!navigator.clipboard || !navigator.clipboard.readText) { switchTab('code'); codeEl.select(); toast('Pega tu plantilla con Ctrl+V'); return; }
  navigator.clipboard.readText().then(function(t){
    if (!t || !/<[a-z!]/i.test(t)) { toast('Lo copiado no parece HTML'); return; }
    setTemplate(t, { record:'now' }); toast('Plantilla pegada');
  }, function(){ switchTab('code'); codeEl.select(); toast('Pega tu plantilla con Ctrl+V'); });
};
function fileName(){ var n = ($('tplName').value || (CFG && CFG.initialName) || 'plantilla').trim(); return n.replace(/[^\w\-]+/g, '_') + '.html'; }
$('btnDownload').onclick = function(){
  var blob = new Blob([currentTemplate()], { type:'text/html;charset=utf-8' });
  var a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = fileName();
  document.body.appendChild(a); a.click(); setTimeout(function(){ URL.revokeObjectURL(a.href); a.remove(); }, 500);
};
$('btnImport').onclick = function(){ $('fileInput').click(); };
$('fileInput').onchange = function(e){
  var f = e.target.files[0]; if (!f) return;
  var r = new FileReader(); r.onload = function(){ setTemplate(String(r.result), { record:'now' }); toast('Archivo cargado: ' + f.name); }; r.readAsText(f);
  e.target.value = '';
};
var resetArmed = false, resetT;
$('btnReset').onclick = function(){
  if (!resetArmed) { resetArmed = true; $('btnReset').textContent = '¿Seguro? Clic otra vez'; resetT = setTimeout(function(){ resetArmed = false; $('btnReset').textContent = 'Restaurar'; }, 3000); return; }
  clearTimeout(resetT); resetArmed = false; $('btnReset').textContent = 'Restaurar';
  setTemplate(ORIGINAL_TEMPLATE, { record:'now' }); setData(JSON.stringify(DEFAULT_DATA, null, 2)); toast('Plantilla restaurada');
};

/* ---------- integración con posMe (CodeIgniter 4) ---------- */
function hdrs(){
  var h = { 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest' };
  if (CFG && CFG.csrfHeader && CFG.csrfHash) h[CFG.csrfHeader] = CFG.csrfHash;
  return h;
}
function updCsrf(c){ if (CFG && c) CFG.csrfHash = c; }
function loadList(selected){
  if (!CFG || !CFG.listUrl) return;
  fetch(CFG.listUrl, { credentials:'same-origin', headers: hdrs() }).then(function(r){ return r.json(); }).then(function(j){
    var sel = $('tplList'); sel.innerHTML = '<option value="">— Plantillas guardadas —</option>';
    (j.items || []).forEach(function(n){ var o = document.createElement('option'); o.value = n; o.textContent = n; if (n === selected) o.selected = true; sel.appendChild(o); });
  }).catch(function(){});
}
function doSave(){
  /* incrustado: Ctrl+S copia el HTML para pegarlo en la tarjeta */
  if (EMBED) { $('btnCopy').click(); return; }
  var t = currentTemplate();
  if (!CFG || !CFG.saveUrl) { $('btnDownload').click(); return; }
  var name = $('tplName').value.trim();
  if (!name) { $('tplName').focus(); toast('Escribe un nombre para la plantilla'); return; }
  var btn = $('btnSave'); btn.disabled = true; btn.textContent = 'Guardando…';
  fetch(CFG.saveUrl, { method:'POST', headers: hdrs(), credentials:'same-origin', body: JSON.stringify({ name: name, template: t }) })
    .then(function(r){ return r.json(); })
    .then(function(j){ if (j.csrf) updCsrf(j.csrf); if (!j.ok) throw new Error(j.message || 'No se pudo guardar'); $('tplName').value = j.name; toast('Plantilla guardada'); loadList(j.name); })
    .catch(function(e){ toast(e.message || 'No se pudo guardar'); })
    .then(function(){ btn.disabled = false; btn.textContent = 'Guardar'; });
}
if (CFG && CFG.saveUrl) {
  $('serverGroup').hidden = false;
  $('tplName').value = CFG.initialName || '';
  $('btnSave').onclick = doSave;
  $('tplList').onchange = function(){
    var n = this.value; if (!n) return;
    fetch(CFG.loadUrl + '/' + encodeURIComponent(n), { credentials:'same-origin', headers: hdrs() }).then(function(r){ return r.json(); }).then(function(j){
      if (!j.ok) throw new Error(j.message); $('tplName').value = j.name; setTemplate(j.template, { record:'now' }); toast('Plantilla "' + j.name + '" cargada');
    }).catch(function(e){ toast(e.message || 'No se pudo cargar'); });
  };
  loadList(CFG.initialName);
}
document.addEventListener('keydown', function(e){
  if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') { e.preventDefault(); doSave(); }
});

/* En el visor de Claude (iframe) las descargas están bloqueadas: se usa Copiar */
var embedded = false; try { embedded = window.self !== window.top; } catch(e){ embedded = true; }
if (embedded && !CFG) $('btnDownload').hidden = true;

/* ---------- arranque ---------- */
codeEl.value = tpl; updGutter();
dataEl.value = dataText;
refreshSide(); updUndo();
loadVisual();
render();

window.__posmeEditor = { ciParse: ciParse, analyze: analyze, protectLoops: protectLoops, get template(){ return currentTemplate(); } };
})();
</script>
</body>
</html>
