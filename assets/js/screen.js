const screenEl = document.getElementById('screen');
const demo = {
  station:'Île-de-France', mode:'departures', theme:'blue', showTicker:true,
  ticker:'Travaux entre Paris Saint-Lazare et Clichy du 18 au 22 mai. Prévoir un allongement du temps de trajet.',
  trains:[
    {time:'08h59',destination:'Versailles Rive Droite',via:'Aubervilliers • La Courneuve • Le Bourget • Drancy • Le Blanc Mesnil',platform:'7'},
    {time:'09h01',destination:'Nanterre Université',via:'La Défense • Puteaux • Suresnes Mont Valérien • Nanterre Préfecture',platform:'4'},
    {time:'09h02',destination:'Saint-Nom la Bretèche',via:'',platform:'10'},
    {time:'09h02',destination:'Ermont Eaubonne',via:'',platform:'20'},
    {time:'09h07',destination:'Mantes la Jolie',via:'',platform:'2'},
    {time:'09h11',destination:'Nanterre Université',via:'',platform:'4'},
    {time:'09h12',destination:'Mantes via Conflans',via:'Poissy • Villennes sur Seine • Vernouillet Verneuil • Les Mureaux',platform:'1'},
    {time:'09h14',destination:'Versailles Rive Droite',via:'Chaville Rive Droite • Viroflay Rive Gauche • Versailles Rive Droite',platform:'7'}
  ]
};
function esc(v){return String(v ?? '').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));}
function now(){return new Date().toLocaleTimeString('fr-FR',{hour:'2-digit',minute:'2-digit'}).replace(':','h');}
function dots(txt){return esc(txt||'').replaceAll(' • ',' <span class="dot">•</span> ');}
function normalizeTime(t){let s=String(t||'').trim(); if(/^\d{2}:\d{2}$/.test(s)) s=s.replace(':','h'); return s;}
function render(data){
  const modeLabel=data.mode==='arrivals'?'Arrivées':'Départs';
  const rows=(data.trains||[]).slice(0,8).map(t=>{
    const large=(t.via||'').trim()!=='';
    return `<div class="gare-row ${large?'large':''}"><div class="gare-time">${esc(normalizeTime(t.time))}</div><div class="gare-dest"><div class="gare-main">${esc(t.destination)}</div>${large?`<div class="gare-via">${dots(t.via)}</div>`:''}</div><div class="gare-platform"><div class="platform-badge">${esc(t.platform||'')}</div></div></div>`;
  }).join('') || '<div class="empty">Aucun train renseigné.</div>';
  const ticker = data.showTicker === false || !data.ticker ? '' : `<div class="info-band"><div class="info-icon">i</div><div class="info-text"><span class="info-marquee">${esc(data.ticker)}</span></div></div>`;
  screenEl.className=`station-screen theme-${esc(data.theme||'blue')}`;
  screenEl.innerHTML=`<section class="board-frame"><header class="screen-header"><div class="screen-title">${modeLabel}</div><div class="screen-subtitle">${esc(data.station||'')}</div></header><div class="watermark">${modeLabel.toLowerCase()}</div><main class="rows">${rows}</main><div class="bottom-labels"><div>heure <b>${now()}</b></div><div>destination</div><div>voie</div></div>${ticker}</section>`;
}
async function load(){
  if(!window.SCREEN_ID){render(demo);return;}
  try{const r=await fetch(`api/load.php?id=${encodeURIComponent(window.SCREEN_ID)}`);const j=await r.json();render(j.ok?j.screen:demo);}catch(e){render(demo);}
}
load();setInterval(()=>{const el=document.querySelector('.bottom-labels b'); if(el) el.textContent=now();},15000);
