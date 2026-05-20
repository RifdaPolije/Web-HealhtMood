function roundedRect(
    ctx,
    x,
    y,
    width,
    height,
    radius,
    color
){

    ctx.beginPath();

    ctx.moveTo(x + radius, y);

    ctx.lineTo(
        x + width - radius,
        y
    );

    ctx.quadraticCurveTo(
        x + width,
        y,
        x + width,
        y + radius
    );

    ctx.lineTo(
        x + width,
        y + height - radius
    );

    ctx.quadraticCurveTo(
        x + width,
        y + height,
        x + width - radius,
        y + height
    );

    ctx.lineTo(
        x + radius,
        y + height
    );

    ctx.quadraticCurveTo(
        x,
        y + height,
        x,
        y + height - radius
    );

    ctx.lineTo(
        x,
        y + radius
    );

    ctx.quadraticCurveTo(
        x,
        y,
        x + radius,
        y
    );

    ctx.closePath();

    ctx.fillStyle = color;

    ctx.fill();
}

/* =========================================
   GET DAY NAME
========================================= */

function getDayName(dateString){

    const date = new Date(dateString);

    const days = [
        'Min',
        'Sen',
        'Sel',
        'Rab',
        'Kam',
        'Jum',
        'Sab'
    ];

    return days[date.getDay()];
}

/* =========================================
   MOOD CHART
========================================= */

function drawMoodChart(canvas, rows){

    const ctx =
        canvas.getContext('2d');

    const width =
        canvas.width;

    const height =
        canvas.height;

    ctx.clearRect(
        0,
        0,
        width,
        height
    );

    const padding = 45;

    const maxValue = 5;

    // background
    ctx.fillStyle = '#d9e3ea';

    ctx.fillRect(
        0,
        0,
        width,
        height
    );

    if(!rows.length){

        ctx.fillStyle = '#333';

        ctx.font = '18px Arial';

        ctx.fillText(
            'Belum ada data mood.',
            40,
            height / 2
        );

        return;
    }

    const points = rows.map(
        (row,index)=>{

            const x =
                padding +
                (
                    index *
                    (
                        width -
                        padding * 2
                    )
                ) /
                (
                    rows.length - 1
                );

            const value =
                Number(row.value);

            const y =
                height -
                padding -
                (
                    value /
                    maxValue
                ) *
                (
                    height -
                    padding * 2
                );

            return {
                x,
                y,
                value,
                date: row.date
            };
        }
    );

    // gradient
    const gradient =
        ctx.createLinearGradient(
            0,
            0,
            0,
            height
        );

    gradient.addColorStop(
        0,
        'rgba(45,145,255,.65)'
    );

    gradient.addColorStop(
        1,
        'rgba(45,145,255,.05)'
    );

    // area fill
    ctx.beginPath();

    ctx.moveTo(
        points[0].x,
        points[0].y
    );

    for(let i=0;i<points.length-1;i++){

        const current =
            points[i];

        const next =
            points[i+1];

        const xc =
            (
                current.x +
                next.x
            ) / 2;

        const yc =
            (
                current.y +
                next.y
            ) / 2;

        ctx.quadraticCurveTo(
            current.x,
            current.y,
            xc,
            yc
        );
    }

    ctx.lineTo(
        width - padding,
        height - padding
    );

    ctx.lineTo(
        padding,
        height - padding
    );

    ctx.closePath();

    ctx.fillStyle =
        gradient;

    ctx.fill();

    // line
    ctx.beginPath();

    ctx.moveTo(
        points[0].x,
        points[0].y
    );

    for(let i=0;i<points.length-1;i++){

        const current =
            points[i];

        const next =
            points[i+1];

        const xc =
            (
                current.x +
                next.x
            ) / 2;

        const yc =
            (
                current.y +
                next.y
            ) / 2;

        ctx.quadraticCurveTo(
            current.x,
            current.y,
            xc,
            yc
        );
    }

    ctx.strokeStyle =
        '#1e8fe0';

    ctx.lineWidth = 6;

    ctx.lineCap = 'round';

    ctx.lineJoin = 'round';

    ctx.stroke();

    // labels
    points.forEach(
        (point,index)=>{

            const row =
                rows[index];

            const day =
                getDayName(
                    row.date
                );

            const date =
                row.date.slice(5);

            ctx.fillStyle =
                '#44545f';

            ctx.font =
                '13px Arial';

            ctx.fillText(
                day,
                point.x - 10,
                height - 28
            );

            ctx.fillText(
                date,
                point.x - 18,
                height - 10
            );
        }
    );
}

/* =========================================
   SLEEP CHART
========================================= */

function drawSleepChart(canvas, rows){

    const ctx =
        canvas.getContext('2d');

    const width =
        canvas.width;

    const height =
        canvas.height;

    ctx.clearRect(
        0,
        0,
        width,
        height
    );

    // background
    ctx.fillStyle =
        '#d9e3ea';

    ctx.fillRect(
        0,
        0,
        width,
        height
    );

    if(!rows.length){

        ctx.fillStyle =
            '#333';

        ctx.font =
            '18px Arial';

        ctx.fillText(
            'Belum ada data tidur.',
            40,
            height / 2
        );

        return;
    }

    const startX = 90;

    const startY = 36;

    const barWidth = 310;

    const barHeight = 20;

    const gap = 36;

    rows.forEach(
        (row,index)=>{

            const value =
                Number(row.value);

            const y =
                startY +
                index * gap;

            const fillWidth =
                (
                    value / 12
                ) *
                barWidth;

            const day =
                getDayName(
                    row.date
                );

            // day
            ctx.fillStyle =
                '#34444f';

            ctx.font =
                '13px Arial';

            ctx.fillText(
                day,
                10,
                y
            );

            // date
            ctx.fillText(
                row.date.slice(5),
                10,
                y + 15
            );

            // bg
            roundedRect(
                ctx,
                startX,
                y - 10,
                barWidth,
                barHeight,
                10,
                '#ececec'
            );

            // fill
            roundedRect(
                ctx,
                startX,
                y - 10,
                fillWidth,
                barHeight,
                10,
                '#536a79'
            );

            // hours
            ctx.fillStyle =
                '#44545f';

            ctx.font =
                '15px Arial';

            ctx.fillText(
                value + ' j',
                startX +
                barWidth +
                14,
                y + 5
            );
        }
    );
}

/* =========================================
   LOAD
========================================= */

async function loadCharts(){

    const moodCanvas =
        document.getElementById(
            'moodChart'
        );

    const sleepCanvas =
        document.getElementById(
            'sleepChart'
        );

    const updated =
        document.getElementById(
            'chartUpdated'
        );

    if(
        !moodCanvas ||
        !sleepCanvas
    ) return;

    try{

        const response =
            await fetch(
                'api_stats.php',
                {
                    cache:'no-store'
                }
            );

        const data =
            await response.json();

        if(data.error){

            if(updated){

                updated.textContent =
                    data.error;

                updated.classList.add(
                    'error'
                );
            }

            return;
        }

        drawMoodChart(
            moodCanvas,
            data.mood
        );

        drawSleepChart(
            sleepCanvas,
            data.sleep
        );

        if(updated){

            updated.textContent =
                'Terakhir diperbarui pukul ' +
                data.updated_at;

            updated.classList.remove(
                'error'
            );
        }

    }catch(error){

        if(updated){

            updated.textContent =
                'Grafik gagal dimuat.';

            updated.classList.add(
                'error'
            );
        }
    }
}

loadCharts();

setInterval(
    loadCharts,
    10000
);