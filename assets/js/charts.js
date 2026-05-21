/* =========================================
   HEALTHMOOD — CHARTS.JS (Redesigned v2)
   Sleep : horizontal rounded bar chart
   Mood  : smooth line chart + gradient fill
========================================= */

/* ── Helpers ── */
function getDayName(dateStr) {
    const days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    return days[new Date(dateStr).getDay()];
}

function drawRoundedRect(ctx, x, y, w, h, r, color) {
    if (w < 1 || h < 1) return;
    r = Math.min(r, w / 2, h / 2);
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.lineTo(x + w - r, y);
    ctx.quadraticCurveTo(x + w, y, x + w, y + r);
    ctx.lineTo(x + w, y + h - r);
    ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
    ctx.lineTo(x + r, y + h);
    ctx.quadraticCurveTo(x, y + h, x, y + h - r);
    ctx.lineTo(x, y + r);
    ctx.quadraticCurveTo(x, y, x + r, y);
    ctx.closePath();
    ctx.fillStyle = color;
    ctx.fill();
}

/* ── Resize canvas to CSS size, return logical size ── */
function resizeCanvas(canvas) {
    const dpr  = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();
    const W    = Math.floor(rect.width)  || 480;
    const H    = Math.floor(rect.height) || 220;
    if (canvas.width !== W * dpr || canvas.height !== H * dpr) {
        canvas.width  = W * dpr;
        canvas.height = H * dpr;
        const ctx = canvas.getContext('2d');
        ctx.scale(dpr, dpr);
    }
    return { ctx: canvas.getContext('2d'), W, H };
}

/* =========================================
   SLEEP CHART — Horizontal Rounded Bars
========================================= */
function drawSleepChart(canvas, rows) {
    const { ctx, W, H } = resizeCanvas(canvas);
    ctx.clearRect(0, 0, W, H);

    /* colours */
    const C_TRACK  = 'rgba(255,255,255,0.45)';
    const C_BAR    = '#2f5c70';
    const C_TEXT   = '#1e3a48';
    const C_MUTED  = '#6899aa';
    const MAX_H    = 10;
    const CORNER   = 9;
    const BAR_H    = 20;

    /* layout */
    const PAD_L  = 54;   /* room for day label */
    const PAD_R  = 46;   /* room for "8 j" label */
    const PAD_T  = 14;
    const PAD_B  = 10;
    const trackW = W - PAD_L - PAD_R;
    const n      = rows.length;

    if (!n) {
        ctx.fillStyle = C_MUTED;
        ctx.font = '14px system-ui';
        ctx.fillText('Belum ada data tidur.', PAD_L, H / 2);
        return;
    }

    const rowH = (H - PAD_T - PAD_B) / n;

    rows.forEach((row, i) => {
        const val  = Math.min(parseFloat(row.value) || 0, MAX_H);
        const day  = getDayName(row.date);
        const cy   = PAD_T + i * rowH + rowH / 2;
        const barY = cy - BAR_H / 2;
        const fill = (val / MAX_H) * trackW;

        /* day label */
        ctx.fillStyle    = C_TEXT;
        ctx.font         = '700 13px "Segoe UI", system-ui, sans-serif';
        ctx.textAlign    = 'right';
        ctx.textBaseline = 'middle';
        ctx.fillText(day, PAD_L - 10, cy);

        /* track (background) */
        drawRoundedRect(ctx, PAD_L, barY, trackW, BAR_H, CORNER, C_TRACK);

        /* filled bar */
        if (fill >= CORNER * 2) {
            drawRoundedRect(ctx, PAD_L, barY, fill, BAR_H, CORNER, C_BAR);
        } else if (val > 0) {
            drawRoundedRect(ctx, PAD_L, barY, CORNER * 2, BAR_H, CORNER, C_BAR);
        }

        /* value label */
        ctx.fillStyle    = val > 0 ? C_TEXT : C_MUTED;
        ctx.font         = '600 12px "Segoe UI", system-ui, sans-serif';
        ctx.textAlign    = 'left';
        ctx.textBaseline = 'middle';
        ctx.fillText(val > 0 ? `${val} j` : '—', PAD_L + trackW + 8, cy);
    });
}

/* =========================================
   MOOD CHART — Smooth Line + Gradient Fill
========================================= */
function drawMoodChart(canvas, rows) {
    const { ctx, W, H } = resizeCanvas(canvas);
    ctx.clearRect(0, 0, W, H);

    const C_LINE  = '#2b6fa8';
    const C_DOT_F = '#ffffff';
    const C_DOT_S = '#2b6fa8';
    const C_TEXT  = '#1e3a48';
    const C_MUTED = '#6899aa';
    const C_GRID  = 'rgba(43,111,168,0.12)';
    const MAX_VAL = 5;

    const PAD_L  = 16;
    const PAD_R  = 16;
    const PAD_T  = 26;   /* room for score labels above dots */
    const PAD_B  = 28;   /* room for day labels below */
    const chartW = W - PAD_L - PAD_R;
    const chartH = H - PAD_T - PAD_B;
    const n      = rows.length;

    if (!n) {
        ctx.fillStyle = C_MUTED;
        ctx.font = '14px system-ui';
        ctx.fillText('Belum ada data mood.', PAD_L, H / 2);
        return;
    }

    /* map rows → canvas coords */
    const pts = rows.map((row, i) => {
        const val = parseFloat(row.value) || 0;
        const x   = n > 1
            ? PAD_L + (i / (n - 1)) * chartW
            : PAD_L + chartW / 2;
        const y   = val > 0
            ? PAD_T + chartH - (val / MAX_VAL) * chartH
            : null;
        return { x, y, val, date: row.date };
    });

    /* horizontal grid lines */
    [1, 2, 3, 4, 5].forEach(v => {
        const gy = PAD_T + chartH - (v / MAX_VAL) * chartH;
        ctx.strokeStyle = C_GRID;
        ctx.lineWidth   = 1;
        ctx.beginPath();
        ctx.moveTo(PAD_L, gy);
        ctx.lineTo(PAD_L + chartW, gy);
        ctx.stroke();
    });

    /* split into contiguous segments (skip null/zero) */
    const segs = [];
    let seg = [];
    pts.forEach((pt, i) => {
        if (pt.y !== null) {
            seg.push(pt);
        } else {
            if (seg.length) { segs.push(seg); seg = []; }
        }
        if (i === pts.length - 1 && seg.length) segs.push(seg);
    });

    segs.forEach(seg => {
        if (!seg.length) return;

        /* gradient fill */
        const grad = ctx.createLinearGradient(0, PAD_T, 0, PAD_T + chartH);
        grad.addColorStop(0, 'rgba(43,111,168,0.32)');
        grad.addColorStop(1, 'rgba(43,111,168,0.00)');

        ctx.beginPath();
        catmullRomPath(ctx, seg);
        ctx.lineTo(seg[seg.length - 1].x, PAD_T + chartH);
        ctx.lineTo(seg[0].x, PAD_T + chartH);
        ctx.closePath();
        ctx.fillStyle = grad;
        ctx.fill();

        /* line stroke */
        ctx.beginPath();
        catmullRomPath(ctx, seg);
        ctx.strokeStyle = C_LINE;
        ctx.lineWidth   = 2.5;
        ctx.lineJoin    = 'round';
        ctx.lineCap     = 'round';
        ctx.stroke();

        /* dots + score labels */
        seg.forEach(pt => {
            /* score above dot */
            ctx.fillStyle    = C_TEXT;
            ctx.font         = '600 11px "Segoe UI", system-ui, sans-serif';
            ctx.textAlign    = 'center';
            ctx.textBaseline = 'bottom';
            ctx.fillText(pt.val, pt.x, pt.y - 7);

            /* dot */
            ctx.beginPath();
            ctx.arc(pt.x, pt.y, 5, 0, Math.PI * 2);
            ctx.fillStyle   = C_DOT_F;
            ctx.fill();
            ctx.strokeStyle = C_DOT_S;
            ctx.lineWidth   = 2.5;
            ctx.stroke();
        });
    });

    /* day labels at bottom */
    pts.forEach(pt => {
        ctx.fillStyle    = C_MUTED;
        ctx.font         = '600 11px "Segoe UI", system-ui, sans-serif';
        ctx.textAlign    = 'center';
        ctx.textBaseline = 'top';
        ctx.fillText(getDayName(pt.date), pt.x, PAD_T + chartH + 6);
    });
}

/* Catmull-Rom smooth path */
function catmullRomPath(ctx, pts) {
    if (pts.length === 1) { ctx.moveTo(pts[0].x, pts[0].y); return; }
    ctx.moveTo(pts[0].x, pts[0].y);
    for (let i = 0; i < pts.length - 1; i++) {
        const p0 = pts[i - 1] || pts[i];
        const p1 = pts[i];
        const p2 = pts[i + 1];
        const p3 = pts[i + 2] || p2;
        const cp1x = p1.x + (p2.x - p0.x) / 6;
        const cp1y = p1.y + (p2.y - p0.y) / 6;
        const cp2x = p2.x - (p3.x - p1.x) / 6;
        const cp2y = p2.y - (p3.y - p1.y) / 6;
        ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, p2.x, p2.y);
    }
}

/* =========================================
   MOOD TOOLTIP on hover
========================================= */
function attachMoodTooltip(canvas, rows) {
    const MAX_VAL = 5;
    const PAD_L   = 16, PAD_R = 16, PAD_T = 26, PAD_B = 28;
    const HIT     = 20;

    let tip = document.getElementById('_hm_tip');
    if (!tip) {
        tip = document.createElement('div');
        tip.id = '_hm_tip';
        Object.assign(tip.style, {
            position:'fixed', pointerEvents:'none',
            background:'#1e3a48', color:'#fff',
            padding:'5px 11px', borderRadius:'8px',
            fontSize:'12px', fontWeight:'700',
            opacity:'0', transition:'opacity .15s',
            zIndex:'9999', whiteSpace:'nowrap',
            boxShadow:'0 3px 10px rgba(0,0,0,.25)',
        });
        document.body.appendChild(tip);
    }

    canvas.addEventListener('mousemove', e => {
        const rect   = canvas.getBoundingClientRect();
        const mx     = e.clientX - rect.left;
        const my     = e.clientY - rect.top;
        const n      = rows.length;
        const chartW = rect.width  - PAD_L - PAD_R;
        const chartH = rect.height - PAD_T  - PAD_B;
        let hit = null;
        rows.forEach((row, i) => {
            const val = parseFloat(row.value) || 0;
            if (!val) return;
            const x = n > 1 ? PAD_L + (i / (n-1)) * chartW : PAD_L + chartW/2;
            const y = PAD_T + chartH - (val / MAX_VAL) * chartH;
            if (Math.hypot(mx-x, my-y) < HIT) hit = { row, val };
        });
        if (hit) {
            tip.textContent = `${getDayName(hit.row.date)}  ${hit.row.date.slice(5)} · Mood ${hit.val}/5`;
            tip.style.left    = (e.clientX + 14) + 'px';
            tip.style.top     = (e.clientY - 32) + 'px';
            tip.style.opacity = '1';
            canvas.style.cursor = 'crosshair';
        } else {
            tip.style.opacity = '0';
            canvas.style.cursor = 'default';
        }
    });
    canvas.addEventListener('mouseleave', () => { tip.style.opacity = '0'; });
}

/* =========================================
   LOAD + AUTO-REFRESH
========================================= */
async function loadCharts() {
    const moodEl  = document.getElementById('moodChart');
    const sleepEl = document.getElementById('sleepChart');
    const updated = document.getElementById('chartUpdated');
    if (!moodEl || !sleepEl) return;

    try {
        const res  = await fetch('api_stats.php', { cache: 'no-store' });
        const data = await res.json();

        if (data.error) {
            if (updated) { updated.textContent = data.error; updated.className = 'message error'; }
            return;
        }

        drawSleepChart(sleepEl, data.sleep);
        drawMoodChart(moodEl,   data.mood);
        attachMoodTooltip(moodEl, data.mood);

        if (updated) {
            updated.textContent = 'Terakhir diperbarui pukul ' + data.updated_at;
            updated.className   = 'chart-updated';
        }
    } catch {
        if (updated) { updated.textContent = 'Grafik gagal dimuat.'; updated.className = 'message error'; }
    }
}

/* redraw on resize */
let _rt;
window.addEventListener('resize', () => { clearTimeout(_rt); _rt = setTimeout(loadCharts, 150); });

loadCharts();
setInterval(loadCharts, 10000);